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
use Illuminate\Support\Str;


class ClientAuthController extends Controller
{
    // Show login form
    public function showLoginForm()
    {
        return view('auth.client_login');
    }

    // Handle login
    public function login(Request $request)
    {
        if (Auth::guard('client')->attempt($request->only('email', 'password'))) {
            
            $client = Auth::guard('client')->user();

            session(['organization_id' => $client->org_setup_id]);

            OrgAccountSession::updateOrCreate(
                ['org_account_id' => $client->id, 'ip_address' => $request->ip()],
                [
                    'id' => (string) Str::uuid(),
                    'user_agent' => $request->header('User-Agent'),
                    'payload' => json_encode([]), // Add payload if needed
                    'last_activity' => now()->timestamp,
                ]
            );


            return redirect()->route('client.dashboard');
        }

        return back()->withErrors(['email' => 'Invalid credentials.']);
    }

    // Handle logout
    public function logout(Request $request)
    {
        
        $client = Auth::guard('client')->user();

        // Remove the session entry on logout
        OrgAccountSession::where('org_account_id', $client->id)
            ->where('ip_address', $request->ip())
            ->delete();

        Auth::guard('client')->logout();
        return redirect()->route('client.login');
    }

    // Show registration form
    public function showRegistrationForm()
    {
        return view('auth.client_register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:org_accounts',
            'password' => 'required|confirmed|min:8',
        ]);

        OrgAccount::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('client.login');
    }

    // Show Forgot Password Form
    public function showForgotPasswordForm()
    {
        return view('auth.client_forgot-password');
    }

    // Handle sending password reset link

    public function sendResetLink(Request $request)
        {
            $request->validate(['email' => 'required|email']);

            $status = Password::broker('client_accounts')->sendResetLink(
                $request->only('email')
            );

            return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
        }


    // Show Reset Password Form
    public function showResetPasswordForm($token)
    {
        return view('auth.client_reset-password', ['token' => $token]);
    }

    // Reset Password
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
            'token' => 'required',
        ]);

        $status = Password::broker('client_accounts')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('client.login')->with('status', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }


    // Show Confirm Password Form
    public function showConfirmPasswordForm()
    {
        return view('auth.client_confirm-password');
    }

    // Confirm Password
    public function confirmPassword(Request $request)
    {
        $request->validate(['password' => 'required']);

        if (Hash::check($request->password, Auth::guard('client')->user()->password)) {
            return redirect()->intended(route('client.dashboard'));
        }

        return back()->withErrors(['password' => 'The provided password does not match our records.']);
    }

    // Client Dashboard
    public function dashboard(Request $request)
    {
        // Retrieve organization ID from the session
        $organizationId = $request->session()->get('organization_id');

        // Retrieve organization details based on organization_id
        $organization = OrgSetup::find($organizationId);

        // Count for filed tax returns
        $filedTaxReturnCount = TaxReturn::where('status', 'Filed')
            ->where('organization_id', $organizationId)
            ->count();

        // Count for sales and purchase transactions
        $totalSalesTransaction = Transactions::where('transaction_type', 'Sales')
            ->where('organization_id', $organizationId)
            ->count();
        $totalPurchaseTransaction = Transactions::where('transaction_type', 'Purchase')
            ->where('organization_id', $organizationId)
            ->count();

        $todayTaxReturns = TaxReturn::where('organization_id', $organizationId)
            ->whereDate('created_at', today())
            ->get();
        $upcomingTaxReturns = TaxReturn::where('organization_id', $organizationId)
            ->whereDate('created_at', '>', today())
            ->get();
        $completedTaxReturns = TaxReturn::where('organization_id', $organizationId)
            ->where('status', 'Completed')
            ->where('organization_id', $organizationId)
            ->get();
        $pendingTaxReturnCount = TaxReturn::where('organization_id', $organizationId)
            ->whereNotIn('status', ['Filed', 'Completed'])
            ->count();


        // Pass the filtered tax returns to the view
        return view('client.dashboard', compact(
            'organization', 
            'filedTaxReturnCount', 
            'totalSalesTransaction', 
            'totalPurchaseTransaction',
            'todayTaxReturns', 
            'upcomingTaxReturns', 
            'completedTaxReturns',
            'pendingTaxReturnCount'
        ));
    }

    //Generated Forms Client page
    public function forms(Request $request)
    {
        $year = $request->input('year');
        $month = $request->input('month');
        $startMonth = $request->input('start_month');
        $endMonth = $request->input('end_month');
        $search = $request->input('search');
        $status = $request->input('status');
        $perPage = $request->input('perPage', 5);

        $query = TaxReturn::with('user'); 

        if ($year) {
            $query->whereYear('created_at', $year);
        }

        if ($month && $year) {
            $query->whereMonth('created_at', $month);
        }

        if ($startMonth && $endMonth && $year) {
            $query->whereMonth('created_at', '>=', $startMonth)
                ->whereMonth('created_at', '<=', $endMonth);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                ->orWhereHas('user', function ($q) use ($search) {
                    $q->where('status', 'LIKE', "%{$search}%");
                });
            });
        }

        $taxReturns = $query->paginate($perPage);

        return view('client.forms', compact('taxReturns'));
    }


}
