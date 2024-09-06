<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class sendPasswordReset extends Controller
{
    /**
     * Handle the sending of the password reset email and redirect.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendPasswordReset(Request $request)
    {
        // Validate the email input
        $request->validate(['email' => 'required|email']);

        // Send the password reset email
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Redirect to the check-mail page with status
        return redirect('check-mail')->with('status', __($status));
    }
}
