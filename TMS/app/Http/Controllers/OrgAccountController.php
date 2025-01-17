<?php

namespace App\Http\Controllers;

use App\Models\OrgAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

        try {
            // Generate a random 8-character password
            $randomPassword = Str::random(8);

            // Create the organization account with a hashed password
            $orgAccount = OrgAccount::create([
                'org_setup_id' => $validatedData['org_setup_id'],
                'email' => $validatedData['email'],
                'password' => Hash::make($randomPassword),
            ]);

            // Log account creation activity
            activity('Org Account Management')
                ->performedOn($orgAccount)
                ->causedBy(Auth::user())
                ->withProperties([
                    'org_setup_id' => $validatedData['org_setup_id'],
                    'email' => $validatedData['email'],
                    'ip' => $request->ip(),
                ])
                ->log('Organization account created for email ' . $validatedData['email'] . '.');

            // Send an email with account details to the user
            $this->sendAccountEmail($validatedData['email'], $randomPassword);

            // Redirect back with a success message
            return redirect()->back()->with('success', 'Account created successfully and email sent.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error creating organization account: ' . $e->getMessage());

            // Redirect back with an error message
            return redirect()->back()->with('error', 'Failed to create the account. Please try again later.');
        }
    }

    protected function sendAccountEmail($email, $password)
    {
        try {
            Mail::send([], [], function ($message) use ($email, $password) {
                $message->to($email)
                    ->subject('Your New Account Details')
                    ->html("<p>Hello,</p>
                        <p>Your account has been successfully created with the following details:</p>
                        <ul>
                            <li>Email: {$email}</li>
                            <li>Password: {$password}</li>
                        </ul>
                        <p>Please log in and change your password at your earliest convenience.</p>");
            });
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error sending account email: ' . $e->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        try {
            $client = OrgAccount::findOrFail($request->input('client_id'));

            // Log account deletion activity
            activity('Org Account Management')
                ->performedOn($client)
                ->causedBy(Auth::user())
                ->withProperties([
                    'client_id' => $client->id,
                    'email' => $client->email,
                    'ip' => $request->ip(),
                ])
                ->log('Organization account deleted for email ' . $client->email . '.');

            $client->delete();

            return redirect()->back()->with('success', 'Client deleted successfully.');
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error deleting organization account: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Failed to delete the client. Please try again later.');
        }
    }
}
