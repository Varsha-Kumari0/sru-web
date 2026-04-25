<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Event::truncate();

        Event::create([
            'title' => 'Emerging Technologies in Electrical & Electronics Communication',
            'excerpt' => 'Join an expert session on the latest communication and embedded systems innovations.',
            'description' => 'Explore breakthroughs in electrical and electronics communication, learn about new tools, and network with alumni working in advanced technology fields.',
            'image' => null,
            'event_type' => 'campus-events',
            'location' => 'Ground floor, Seminar Hall, Block II',
            'start_at' => '2026-03-26 13:00:00',
            'end_at' => '2026-03-26 15:00:00',
            'registration_link' => null,
        ]);

        Event::create([
            'title' => 'SparkRill’26 - SR University Annual Cultural Festival',
            'excerpt' => 'Celebrate the annual campus fest with performances, alumni meetups, and cultural showcases.',
            'description' => 'Join us for the biggest cultural festival of the year featuring music, dance, drama, and alumni networking sessions on campus.',
            'image' => null,
            'event_type' => 'campus-events',
            'location' => 'SR University Campus',
            'start_at' => '2026-01-26 17:30:00',
            'end_at' => '2026-01-26 20:30:00',
            'registration_link' => null,
        ]);

        Event::create([
            'title' => 'Beyond the Degree: Building a Career in Global Agribusiness',
            'excerpt' => 'A webinar for alumni and students interested in agribusiness and international career paths.',
            'description' => 'Hear from successful alumni leaders in agribusiness and gain practical tips for career growth, entrepreneurship, and global opportunities.',
            'image' => null,
            'event_type' => 'webinars',
            'location' => 'Seminar Hall, SR University',
            'start_at' => '2026-04-10 16:00:00',
            'end_at' => '2026-04-10 18:00:00',
            'registration_link' => 'https://example.com/register-webinar',
        ]);

        Event::create([
            'title' => 'SR University Alumni Reunion 2025',
            'excerpt' => 'Reconnect with your batchmates and celebrate five years of alumni growth.',
            'description' => 'Enjoy a reunion evening with fellow alumni, campus tours, and special sessions from university leadership.',
            'image' => null,
            'event_type' => 'reunions',
            'location' => 'SRiX Auditorium',
            'start_at' => '2025-12-18 18:00:00',
            'end_at' => '2025-12-18 21:00:00',
            'registration_link' => null,
        ]);

        Event::create([
            'title' => 'Campus Hackathon 2026',
            'excerpt' => 'A 24-hour hackathon for students and alumni to build real solutions together.',
            'description' => 'Participate in teams, pitch ideas, and compete for prizes while collaborating with mentors and SRU alumni.',
            'image' => null,
            'event_type' => 'hackathons',
            'location' => 'Innovation Lab, SR University',
            'start_at' => '2026-05-20 09:00:00',
            'end_at' => '2026-05-21 09:00:00',
            'registration_link' => 'https://example.com/register-hackathon',
        ]);
    }
}
