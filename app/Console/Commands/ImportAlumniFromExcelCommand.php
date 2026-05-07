<?php

namespace App\Console\Commands;

use App\Models\Profile;
use App\Models\Professional;
use App\Models\Skill;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Shuchkin\SimpleXLSX;
use Throwable;

class ImportAlumniFromExcelCommand extends Command
{
    protected $signature = 'alumni:import
        {file=sampledata/B TECH.xlsx : Relative path to the XLSX file}
        {--dry-run : Validate rows without writing to the database}
        {--skip-existing : Skip users that already exist by email}';

    protected $description = 'Import alumni from XLSX into users/profiles without sending password emails';

    public function handle(): int
    {
        $fileArgument = (string) $this->argument('file');
        $filePath = base_path($fileArgument);

        if (!is_file($filePath)) {
            $this->error("File not found: {$filePath}");
            return self::FAILURE;
        }

        $xlsx = SimpleXLSX::parse($filePath);
        if (!$xlsx) {
            $this->error('Unable to parse XLSX: ' . SimpleXLSX::parseError());
            return self::FAILURE;
        }

        $sheetCount = count($xlsx->sheetNames());
        if ($sheetCount < 1) {
            $this->warn('No worksheets found in the provided file.');
            return self::SUCCESS;
        }

        $isDryRun = (bool) $this->option('dry-run');
        $skipExisting = (bool) $this->option('skip-existing');

        $created = 0;
        $updated = 0;
        $skipped = 0;
        $errors = 0;
        $processed = 0;
        $eligible = 0;

        for ($sheetIndex = 0; $sheetIndex < $sheetCount; $sheetIndex++) {
            $rawRows = $xlsx->rows($sheetIndex);
            $rows = is_array($rawRows) ? $rawRows : iterator_to_array($rawRows, false);
            if (count($rows) < 2) {
                continue;
            }

            $headerRow = (array) array_shift($rows);
            $headers = array_map(fn ($value) => $this->normalizeKey((string) $value), $headerRow);

            foreach ($rows as $index => $row) {
                $lineNumber = $index + 2;
                $processed++;
                $data = $this->combineRow($headers, (array) $row);

                $email = strtolower(trim((string) ($data['email_id'] ?: $data['secondary_email'])));
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $skipped++;
                    $this->warn("Sheet " . ($sheetIndex + 1) . ", row {$lineNumber}: skipped due to missing/invalid email.");
                    continue;
                }

                $eligible++;

                try {
                $fullName = $this->firstNonEmpty([
                    $data['name'],
                    trim((string) ($data['salutation'] . ' ' . $data['name'])),
                ]) ?: 'Alumni User';

                [$firstName, $lastName] = $this->splitName($fullName);

                $profileData = [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'gender' => $this->nullable($data['gender']),
                    'full_name' => $fullName,
                    'mobile' => $this->firstNonEmpty([$data['mobile_phone_no'], $data['home_phone_no'], $data['office_phone_no']]) ?: '-',
                    'contact_email' => $this->nullable($this->firstNonEmpty([$data['secondary_email'], $email])),
                    'city' => $this->firstNonEmpty([$data['current_location'], $data['correspondence_city'], $data['home_town']]) ?: '-',
                    'country' => $this->firstNonEmpty([$data['correspondence_country']]) ?: '-',
                    'linkedin' => $this->nullable($data['linkedin_link']),
                    'facebook' => $this->nullable($data['facebook_link']),
                    'twitter' => $this->nullable($data['twitter_link']),
                    'degree' => $this->firstNonEmpty([$data['course'], $data['educational_course']]) ?: 'N/A',
                    'branch' => $this->firstNonEmpty([$data['stream']]) ?: 'N/A',
                    'passing_year' => $this->nullable($this->firstNonEmpty([$data['course_end_year'], $data['end_year']])),
                    'current_status' => $this->nullable($data['profile_type']),
                    'company' => $this->nullable($data['company']),
                    'employment_from' => $this->normalizeDate($this->firstNonEmpty([$data['faculty_start_year'], $data['start_year']])),
                    'employment_to' => $this->nullable($this->firstNonEmpty([$data['faculty_end_year'], $data['end_year']])),
                    'study_institution' => $this->nullable($this->firstNonEmpty([$data['educational_institute'], $data['institution_name']])),
                    'study_degree' => $this->nullable($data['educational_course']),
                    'study_branch' => $this->nullable($data['stream']),
                    'study_from' => $this->nullable($this->firstNonEmpty([$data['course_start_year'], $data['start_year']])),
                    'study_to' => $this->nullable($this->firstNonEmpty([$data['course_end_year'], $data['end_year']])),
                    'description' => $this->buildDescription($data),
                ];

                $professionalData = [
                    'organization' => $this->firstNonEmpty([$data['company'], $data['faculty_institute']]),
                    'industry' => $this->nullable($this->firstNonEmpty([$data['industries_worked_in'], $data['faculty_function']])),
                    'role' => $this->nullable($this->firstNonEmpty([$data['position'], $data['roles_played'], $data['faculty_job_title']])),
                    'from' => $this->nullable($this->firstNonEmpty([$data['faculty_start_year'], $data['start_year']])),
                    'to' => $this->nullable($this->firstNonEmpty([$data['faculty_end_year'], $data['end_year']])),
                    'location' => $this->nullable($this->firstNonEmpty([$data['current_location'], $data['correspondence_city']])),
                ];

                $skills = $this->parseSkills($data['professional_skills']);

                    if ($isDryRun) {
                        continue;
                    }

                    DB::transaction(function () use (
                        $email,
                        $fullName,
                        $profileData,
                        $professionalData,
                        $skills,
                        $skipExisting,
                        &$created,
                        &$updated,
                        &$skipped
                    ) {
                        $user = User::query()->where('email', $email)->first();

                    if (!$user) {
                        $user = User::query()->create([
                            'name' => $fullName,
                            'email' => $email,
                            'password' => Hash::make(Str::random(40)),
                            'role' => 'user',
                        ]);
                        $created++;
                    } else {
                        if ($skipExisting) {
                            $skipped++;
                            return;
                        }

                        $user->update([
                            'name' => $fullName !== '' ? $fullName : ($user->name ?: 'Alumni User'),
                            'role' => $user->role ?: 'user',
                        ]);
                        $updated++;
                    }

                    Profile::query()->updateOrCreate(
                        ['user_id' => $user->id],
                        $profileData
                    );

                    if ($this->hasAnyValue($professionalData)) {
                        Professional::query()->updateOrCreate(
                            ['user_id' => $user->id],
                            [
                                'organization' => $professionalData['organization'] ?: 'N/A',
                                'industry' => $professionalData['industry'] ?: 'N/A',
                                'role' => $professionalData['role'] ?: 'N/A',
                                'from' => $professionalData['from'] ?: 'N/A',
                                'to' => $professionalData['to'],
                                'location' => $professionalData['location'] ?: 'N/A',
                            ]
                        );
                    }

                        foreach ($skills as $skillName) {
                            Skill::query()->firstOrCreate(
                                ['user_id' => $user->id, 'name' => $skillName],
                                ['level' => 'beginner', 'endorsements_count' => 0]
                            );
                        }
                    });
                } catch (Throwable $exception) {
                    $errors++;
                    $this->error("Sheet " . ($sheetIndex + 1) . ", row {$lineNumber}: {$exception->getMessage()}");
                }
            }
        }

        $this->newLine();
        $this->info('Import finished.');
        $this->line('Processed rows: ' . $processed);
        $this->line('Eligible rows:  ' . $eligible);
        $this->line('Created: ' . $created);
        $this->line('Updated: ' . $updated);
        $this->line('Skipped: ' . $skipped);
        $this->line('Errors:  ' . $errors);

        if ($isDryRun) {
            $this->comment('Dry run mode: no database rows were changed.');
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }

    /**
     * @param array<int, string> $headers
     * @param array<int, mixed> $row
     * @return array<string, string>
     */
    private function combineRow(array $headers, array $row): array
    {
        $data = [];

        foreach ($headers as $index => $header) {
            if ($header === '') {
                continue;
            }

            $value = isset($row[$index]) ? trim((string) $row[$index]) : '';
            $data[$header] = $value;
        }

        return $data + [
            'salutation' => '',
            'name' => '',
            'gender' => '',
            'date_of_birth' => '',
            'label' => '',
            'email_id' => '',
            'secondary_email' => '',
            'mobile_phone_no' => '',
            'home_phone_no' => '',
            'office_phone_no' => '',
            'current_location' => '',
            'home_town' => '',
            'correspondence_address' => '',
            'correspondence_city' => '',
            'correspondence_state' => '',
            'correspondence_country' => '',
            'correspondence_pincode' => '',
            'course' => '',
            'institution_name' => '',
            'stream' => '',
            'course_start_year' => '',
            'course_end_year' => '',
            'profile_type' => '',
            'company' => '',
            'position' => '',
            'member_roles' => '',
            'chapter' => '',
            'educational_course' => '',
            'educational_institute' => '',
            'start_year' => '',
            'end_year' => '',
            'facebook_link' => '',
            'linkedin_link' => '',
            'twitter_link' => '',
            'website_link' => '',
            'work_experiencein_years' => '',
            'professional_skills' => '',
            'industries_worked_in' => '',
            'roles_played' => '',
            'faculty_job_title' => '',
            'faculty_institute' => '',
            'faculty_function' => '',
            'faculty_start_year' => '',
            'faculty_end_year' => '',
            'admin_note' => '',
            'roll_no' => '',
            'employee_id' => '',
        ];
    }

    private function normalizeKey(string $value): string
    {
        $value = strtolower(trim($value));
        $value = preg_replace('/\s+/', '_', $value) ?? '';
        $value = str_replace([':', '.', '-', '/', '(', ')'], ['_', '', '_', '_', '', ''], $value);
        $value = preg_replace('/_+/', '_', $value) ?? '';

        return trim($value, '_');
    }

    /**
     * @param array<int, string|null> $values
     */
    private function firstNonEmpty(array $values): ?string
    {
        foreach ($values as $value) {
            $clean = trim((string) $value);
            if ($clean !== '') {
                return $clean;
            }
        }

        return null;
    }

    /**
     * @return array{0: string, 1: string}
     */
    private function splitName(string $fullName): array
    {
        $parts = preg_split('/\s+/', trim($fullName)) ?: [];

        if (count($parts) <= 1) {
            return [trim($fullName), ''];
        }

        $first = array_shift($parts) ?: '';
        $last = trim(implode(' ', $parts));

        return [$first, $last];
    }

    private function nullable(?string $value): ?string
    {
        $value = trim((string) $value);
        return $value === '' ? null : $value;
    }

    private function normalizeDate(?string $value): ?string
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        $normalized = strtolower($value);
        if (in_array($normalized, ['present', 'current', 'ongoing', 'na', 'n/a', '-', '?'], true)) {
            return null;
        }

        if (preg_match('/^\d{4}$/', $value) === 1) {
            return $value . '-01-01';
        }

        $timestamp = strtotime($value);
        if ($timestamp === false) {
            return null;
        }

        return date('Y-m-d', $timestamp);
    }

    /**
     * @param array<string, string> $row
     */
    private function buildDescription(array $row): ?string
    {
        $chunks = [];

        $pairs = [
            'Label' => $row['label'] ?? '',
            'Roll No' => $row['roll_no'] ?? '',
            'Employee ID' => $row['employee_id'] ?? '',
            'Institution Name' => $row['institution_name'] ?? '',
            'Correspondence Address' => $row['correspondence_address'] ?? '',
            'Correspondence State' => $row['correspondence_state'] ?? '',
            'Correspondence Pincode' => $row['correspondence_pincode'] ?? '',
            'Website' => $row['website_link'] ?? '',
            'Work Experience (years)' => $row['work_experiencein_years'] ?? '',
            'Admin Note' => $row['admin_note'] ?? '',
            'Member Roles' => $row['member_roles'] ?? '',
            'Chapter' => $row['chapter'] ?? '',
            'Date of Birth' => $row['date_of_birth'] ?? '',
        ];

        foreach ($pairs as $key => $value) {
            $value = trim((string) $value);
            if ($value !== '') {
                $chunks[] = $key . ': ' . $value;
            }
        }

        return empty($chunks) ? null : implode("\n", $chunks);
    }

    /**
     * @return array<int, string>
     */
    private function parseSkills(?string $raw): array
    {
        $raw = trim((string) $raw);
        if ($raw === '') {
            return [];
        }

        $parts = preg_split('/[,;|]/', $raw) ?: [];

        $skills = array_values(array_unique(array_filter(array_map(function ($item) {
            $skill = trim((string) $item);
            return $skill === '' ? null : $skill;
        }, $parts))));

        return $skills;
    }

    /**
     * @param array<string, string|null> $values
     */
    private function hasAnyValue(array $values): bool
    {
        foreach ($values as $value) {
            if ($this->nullable($value) !== null) {
                return true;
            }
        }

        return false;
    }
}
