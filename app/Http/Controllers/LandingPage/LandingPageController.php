<?php

namespace App\Http\Controllers\LandingPage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LandingPageController extends Controller
{
    public function index()
    {
        $linkApp = [
            'apple_link' => '/landing-page-banner',
            'android_link' => '/landing-page-banner',
            'apple_link_s1' => 'https://www.google.com.vn',
            'android_link_s1' => 'https://www.google.com.vn',
        ];
        return view('components.landing-page', compact('linkApp'));
    }

    public function banner()
    {
        return view('components.landing-page-banner');
    }
}
