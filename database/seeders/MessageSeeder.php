<?php

namespace Database\Seeders;

use App\Models\Message;
use App\Models\User;
use Illuminate\Database\Seeder;

class MessageSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'user')->get();

        if ($users->count() < 2) {
            return; // Need at least 2 users to create messages
        }

        $messages = [
            [
                'content' => 'Hi! I saw your profile and I\'m impressed with your experience in software development. Would love to connect!',
                'subject' => 'Professional Connection Request',
            ],
            [
                'content' => 'Hello! Congratulations on your recent achievement. I\'d like to discuss potential collaboration opportunities.',
                'subject' => 'Collaboration Opportunity',
            ],
            [
                'content' => 'Hey there! I noticed we have similar professional backgrounds. Would you be interested in sharing some insights?',
                'subject' => 'Professional Insights',
            ],
            [
                'content' => 'Hi! I\'m reaching out regarding the alumni networking event. Are you planning to attend?',
                'subject' => 'Alumni Event',
            ],
            [
                'content' => 'Hello! Your skills in project management caught my attention. I have a project that might interest you.',
                'subject' => 'Project Opportunity',
            ],
            [
                'content' => 'Hi! I wanted to congratulate you on your recent promotion. That\'s fantastic news!',
                'subject' => 'Congratulations',
            ],
            [
                'content' => 'Hello! I\'m organizing a tech meetup and thought you might be interested in speaking or attending.',
                'subject' => 'Tech Meetup Invitation',
            ],
            [
                'content' => 'Hey! Your experience in data science is exactly what our team needs. Let\'s discuss potential opportunities.',
                'subject' => 'Career Opportunity',
            ],
        ];

        // Create messages between random users
        for ($i = 0; $i < 15; $i++) {
            $sender = $users->random();
            $receiver = $users->where('id', '!=', $sender->id)->random();

            $messageData = $messages[array_rand($messages)];

            Message::create([
                'sender_id' => $sender->id,
                'receiver_id' => $receiver->id,
                'subject' => $messageData['subject'],
                'content' => $messageData['content'],
                'is_read' => rand(0, 1),
                'read_at' => rand(0, 1) ? now()->subDays(rand(1, 7)) : null,
            ]);
        }
    }
}