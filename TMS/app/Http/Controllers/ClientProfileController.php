<?php

namespace App\Http\Controllers;

use App\Models\OrgSetup;
use App\Models\Transactions;
use App\Models\OrgAccount;
use App\Models\TaxReturn;
use App\Models\OrgAccountSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Detection\MobileDetect;
use Illuminate\Support\Str;

class ClientProfileController extends Controller
{
public function profile()
{
    $client = Auth::guard('client')->user();

    if (!$client) {
        return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
    }

    // Retrieve client sessions and add agentInfo
    $clientSessions = OrgAccountSession::where('org_account_id', $client->id)->get();
    $sessionsWithAgentInfo = $clientSessions->map(function ($session) {
        $detect = new MobileDetect();
        $detect->setUserAgent($session->user_agent);

        $sessionData = (object) [
            'id' => $session->id,
            'ip_address' => $session->ip_address,
            'user_agent' => $session->user_agent,
            'last_activity' => $session->last_activity,
            'agentInfo' => (object) [
                'platform' => $detect->isMobile() ? 'Mobile' : ($detect->isTablet() ? 'Tablet' : 'Desktop'),
                'browser' => $this->getBrowserFromUserAgent($session->user_agent),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->is_current_device ?? false,
                'last_active' => $session->last_activity,
            ]
        ];

        return $sessionData;
    });

    $profilePhotoUrl = $client->profile_photo_path
        ? asset('storage/' . $client->profile_photo_path)
        : null;

    return view('client.profile', [
        'user' => $client,
        'sessions' => $sessionsWithAgentInfo,
        'profilePhotoUrl' => $profilePhotoUrl,
    ]);
}


    private function getBrowserFromUserAgent($userAgent)
    {
        if (strpos($userAgent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($userAgent, 'Safari') !== false && strpos($userAgent, 'Chrome') === false) {
            return 'Safari';
        } elseif (strpos($userAgent, 'MSIE') !== false || strpos($userAgent, 'Trident') !== false) {
            return 'Internet Explorer';
        }
        return 'Unknown';
    }



    public function updateProfilePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'profile_photo.required' => 'Please upload a profile photo.',
            'profile_photo.image' => 'The profile photo must be an image file.',
            'profile_photo.mimes' => 'The profile photo must be a file of type: jpeg, png, jpg, gif.',
            'profile_photo.max' => 'The profile photo may not be larger than 2 MB.',
        ]);

        $user = Auth::guard('client')->user();

        if ($user instanceof OrgAccount) {
            // Delete old profile photo if it exists
            if ($user->profile_photo_path) {
                Storage::delete('public/' . $user->profile_photo_path);
            }

            // Store new profile photo in the custom directory and assign the path
            $path = $request->file('profile_photo')->storeAs('public/profile', time() . '-' . $request->file('profile_photo')->getClientOriginalName());
            
            // Update profile_photo_path with the stored file path (removing 'public/' prefix for consistency)
            $user->profile_photo_path = str_replace('public/', '', $path);
            $user->save();

            return redirect()->back()->with('status', 'profile_updated');

        }

        return back()->withErrors(['error' => 'User not authenticated or incorrect user type.']);
    }

    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:org_accounts,email',
            'password' => 'required'
        ], [
            'email.required' => 'The email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address is already in use.',
            'password.required' => 'Please enter your password to confirm.'
        ]);

        $user = Auth::guard('client')->user();

        if ($user instanceof OrgAccount) {
            // Confirm the password
            if (!Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => 'Incorrect password.']);
            }

            // Update the email if password is correct
            $user->email = $request->email;
            $user->save();

            return redirect()->back()->with('status', 'email_updated');

        }

        return back()->withErrors(['error' => 'User not authenticated or incorrect user type.']);
    }

}
