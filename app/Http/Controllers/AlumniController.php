<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlumniController extends Controller
{
    public function index()
    {
        $news = [
            [
                'category' => 'Announcement',
                'title'    => 'Annual Alumni Meet 2025 – Registration Now Open',
                'excerpt'  => 'Join us for our biggest reunion yet! Connect with batchmates from across the globe, attend keynote talks, and relive your campus memories.',
                'date'     => 'Apr 20, 2025',
            ],
            [
                'category' => 'Achievement',
                'title'    => 'Alumni Startup Raises ₹50 Cr in Series A Funding',
                'excerpt'  => 'Our very own Priya Sharma (Batch of 2015) has secured a major funding round for her EdTech venture, marking a milestone for alumni entrepreneurship.',
                'date'     => 'Apr 15, 2025',
            ],
            [
                'category' => 'Campus News',
                'title'    => 'New Research Lab Inaugurated — Alumni Contributed ₹2 Cr',
                'excerpt'  => 'Thanks to generous alumni donations, the university has launched a state-of-the-art AI & Robotics research lab available to current students.',
                'date'     => 'Apr 10, 2025',
            ],
        ];

        $events = [
            [
                'title'       => 'Annual Alumni Meet 2025',
                'date'        => '2025-05-10',
                'time'        => '10:00 AM – 6:00 PM',
                'location'    => 'Main Auditorium, Campus',
                'description' => 'A grand reunion with networking sessions, cultural programmes, and panel discussions.',
            ],
            [
                'title'       => 'Career Mentorship Workshop',
                'date'        => '2025-05-22',
                'time'        => '2:00 PM – 5:00 PM',
                'location'    => 'Seminar Hall B',
                'description' => 'Senior alumni guide students and juniors through career planning and industry insights.',
            ],
            [
                'title'       => 'Startup Pitch Competition',
                'date'        => '2025-06-05',
                'time'        => '9:00 AM – 4:00 PM',
                'location'    => 'Innovation Hub',
                'description' => 'Alumni entrepreneurs pitch ideas to a panel of investors and industry veterans.',
            ],
            [
                'title'       => 'Alumni Cricket Tournament',
                'date'        => '2025-06-15',
                'time'        => '7:00 AM – 1:00 PM',
                'location'    => 'College Sports Ground',
                'description' => 'Friendly cricket matches between batches — register your team of 11!',
            ],
        ];

        $jobs = [
            [
                'title'      => 'Senior Software Engineer',
                'company'    => 'Zoho Corporation',
                'location'   => 'Chennai, India',
                'type'       => 'Full-time',
                'experience' => '3–5 Years',
            ],
            [
                'title'      => 'Product Manager',
                'company'    => 'Freshworks',
                'location'   => 'Remote',
                'type'       => 'Full-time',
                'experience' => '4+ Years',
            ],
            [
                'title'      => 'Data Analyst Intern',
                'company'    => 'Tata Consultancy Services',
                'location'   => 'Bangalore, India',
                'type'       => 'Internship',
                'experience' => 'Fresher',
            ],
            [
                'title'      => 'UX Designer',
                'company'    => 'PhonePe',
                'location'   => 'Hybrid – Pune',
                'type'       => 'Contract',
                'experience' => '2–4 Years',
            ],
        ];

        return view('alumni.index', compact('news', 'events', 'jobs'));
    }
}