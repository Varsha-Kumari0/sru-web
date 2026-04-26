<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::where('status', 'active')->latest()->get();
        
        return view('testimonials.index', compact('testimonials'));
    }
}
