<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\RedirectResponse;

class CustomVerificationController extends Controller
{
    /**
     * Handle the email verification.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(EmailVerificationRequest $request): RedirectResponse
    {
        $request->fulfill(); // Marks the email as verified

        // Redirect to the register success page after verification
        return redirect()->route('register-success-page');
    }
}   

