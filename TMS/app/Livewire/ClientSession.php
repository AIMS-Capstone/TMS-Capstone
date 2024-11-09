<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Detection\MobileDetect;


class ClientSession extends Component
{
    public $sessions;
    public $confirmingLogout = false;
    public $password;
    public $logoutError = ''; // Property to hold logout error message
    public $logoutSuccess = ''; // Property to hold logout success message

    public function mount()
    {
        $this->sessions = $this->getClientSessions();
    }

    public function getClientSessions()
    {
        $currentSessionId = session()->getId();

        $sessions = DB::table('org_account_sessions')
            ->where('org_account_id', Auth::guard('client')->id())
            ->get()
            ->map(function ($session) use ($currentSessionId) {
                $detect = new MobileDetect();
                $detect->setUserAgent($session->user_agent);

                $session->agentInfo = (object) [
                    'platform' => $detect->isMobile() ? 'Mobile' : ($detect->isTablet() ? 'Tablet' : 'Desktop'),
                    'browser' => $this->getBrowserFromUserAgent($session->user_agent),
                    'ip_address' => $session->ip_address,
                    'last_active' => $session->last_activity,
                ];

                // Ensure is_current_device is correctly set based on session ID matching
                $session->is_current_device = ($session->id === $currentSessionId);

                // Debug to check if the session is identified as the current device
                if ($session->is_current_device) {
                    dd($session->id, $currentSessionId); // This should print the matching IDs
                }

                return $session;
            });

        return $sessions;
    }

    // Helper function ni krazyNig
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

    public function confirmLogout()
    {
        $this->confirmingLogout = true;
    }

    public function logoutOtherBrowserSessions()
    {
        // Ensure password is correct before logging out other sessions
        if (!Auth::guard('client')->validate(['id' => Auth::guard('client')->id(), 'password' => $this->password])) {
            $this->addError('password', __('The password is incorrect.'));
            $this->logoutError = __('The password is incorrect.'); // Set error message
            return;
        }

        $currentSessionId = session()->getId();

        // Delete all sessions except the current one for the user
        $deleted = DB::table('org_account_sessions')
            ->where('org_account_id', Auth::guard('client')->id())
            ->where('id', '!=', $currentSessionId)
            ->delete();

        // Check if any sessions were deleted
        if ($deleted === 0) {
            $this->logoutError = __('No other sessions were found to log out.');
            return;
        }

        // Clear password, reset confirmation, and set success message
        $this->password = '';
        $this->logoutError = ''; // Clear any previous errors
        $this->logoutSuccess = __('Successfully logged out from other sessions.');
        $this->confirmingLogout = false;
        $this->sessions = $this->getClientSessions(); // Refresh sessions
    }

    public function render()
    {
        return view('livewire.client-session', [
            'sessions' => $this->sessions,
            'logoutError' => $this->logoutError,
            'logoutSuccess' => $this->logoutSuccess,
        ]);
    }
}
