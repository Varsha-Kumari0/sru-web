<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Ravula Shirisha',
                'department' => 'CSE',
                'year_from' => 2015,
                'year_to' => 2019,
                'position' => 'Project Engineer',
                'company' => 'Wipro',
                'content' => 'The internship at SRIX incubator gave me the option to apply the knowledge gained in the classroom in a real world environment. Working with the Entrepreneurs was the real learning experience that offered me a chance to work in a corporate environment and this type of ecosystem helped me to gain a job in MNC',
                'image' => 'testimonial1.svg',
                'status' => 'active'
            ],
            [
                'name' => 'Nikhil Kashyap Karri',
                'department' => 'EEE',
                'year_from' => 2006,
                'year_to' => 2010,
                'position' => 'Software Engineer',
                'company' => 'Capgemini',
                'content' => '4 years, Countless memories, that perfectly sums up my journey @SRU. It was a roller-coaster ride throughout the 1460 days; I was associated with this college. If all the highs are cherished, so are the lows. My graduation now remains one of my reasons to smile. It taught me how to learn, how to grow & most importantly nurtured the leader in me',
                'image' => 'testimonial2.svg',
                'status' => 'active'
            ],
            [
                'name' => 'Savant Arthi',
                'department' => 'ECE',
                'year_from' => 2010,
                'year_to' => 2014,
                'position' => 'Senior Associate Consultant',
                'company' => 'Infosys',
                'content' => 'Opportunities in SRU gave me a platform for my life today. I started seeing life with my field of choice when I stepped into SRU. SRU played a vital role in shaping my career. All the confidence, motivation because of which I stood up has become a part of me from my graduation. I believe, the only way to be truly satisfied is to believe and love the work you do',
                'image' => 'testimonial3.svg',
                'status' => 'active'
            ],
            [
                'name' => 'Priya Sharma',
                'department' => 'CSE',
                'year_from' => 2018,
                'year_to' => 2022,
                'position' => 'Senior Developer',
                'company' => 'TCS',
                'content' => 'SRU provided me with excellent mentorship and practical exposure. The faculty was always supportive and the curriculum was industry-relevant. The networking opportunities I got here have been invaluable in my professional journey. I am proud to be an SRU alumnus.',
                'image' => 'testimonial4.svg',
                'status' => 'active'
            ],
            [
                'name' => 'Rajesh Kumar',
                'department' => 'Mechanical',
                'year_from' => 2012,
                'year_to' => 2016,
                'position' => 'Project Manager',
                'company' => 'L&T',
                'content' => 'The skills I acquired at SRU have been instrumental in my career growth. The hands-on projects and industry collaborations gave me a competitive edge in the job market. I would definitely recommend SRU to anyone seeking quality education.',
                'image' => 'testimonial5.svg',
                'status' => 'active'
            ],
            [
                'name' => 'Anjali Desai',
                'department' => 'Civil',
                'year_from' => 2014,
                'year_to' => 2018,
                'position' => 'Structural Engineer',
                'company' => 'Gammon India',
                'content' => 'My time at SRU was transformative. The rigorous coursework combined with practical assignments prepared me well for the industry. The college\'s emphasis on both technical and soft skills made all the difference in my professional success.',
                'image' => 'testimonial6.svg',
                'status' => 'active'
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
