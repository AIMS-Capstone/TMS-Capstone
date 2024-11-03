<?php

namespace App\Http\Controllers;

use App\Models\OrgAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class OrgAccountController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'org_setup_id' => 'required|exists:org_setups,id', // Validate organization ID
            'email' => 'required|email|unique:org_accounts,email',
        ]);

        // Generate a random 8-character password
        $randomPassword = Str::random(8);

        // Create the organization account with a hashed password
        OrgAccount::create([
            'org_setup_id' => $validatedData['org_setup_id'],
            'email' => $validatedData['email'],
            'password' => Hash::make($randomPassword),
        ]);

        // Send an email with account details to the user
        $this->sendAccountEmail($validatedData['email'], $randomPassword);

        // Optionally, redirect back with a success message
        return redirect()->back()->with('success', 'Account created successfully and email sent.');
    }

    protected function sendAccountEmail($email, $password)
    {
        Mail::send([], [], function ($message) use ($email, $password) {
            $message->to($email)
                ->subject('Your New Account Details')
                ->html("
                    <p>Hello,</p>
                    <p>Your account has been successfully created with the following details:</p>
                    <ul>
                        <li>Email: {$email}</li>
                        <li>Password: {$password}</li>
                    </ul>
                    <p>Please log in and change your password at your earliest convenience.</p>
                ");
        });
    }
    public function destroy(Request $request)
{
    $client = OrgAccount::findOrFail($request->input('client_id'));
    $client->delete();

    return redirect()->back()->with('success', 'Client deleted successfully.');
}
    
}
