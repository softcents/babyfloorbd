<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class NewsletterController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = strtolower(trim($request->email));

        $exists = NewsletterSubscriber::where('email', $email)->first();

        if ($exists) {
            Toastr::info('You are already subscribed!', 'Newsletter');
            return back()->with('newsletter_success', 'আপনি ইতিমধ্যে সাবস্ক্রাইব করেছেন!');
        }

        NewsletterSubscriber::create([
            'email'  => $email,
            'status' => 1,
        ]);

        Toastr::success('Thank you for subscribing to our newsletter!', 'Newsletter');
        return back()->with('newsletter_success', 'ধন্যবাদ! আপনি সফলভাবে সাবস্ক্রাইব করেছেন।');
    }
}
