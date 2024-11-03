<?php

namespace App\Http\Controllers;

use App\Models\OrgAccount;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        // Get search and status input
        $userSearch = $request->input('user_search'); // Change to user_search for clarity
        $clientSearch = $request->input('client_search'); // New search parameter for clients
        $status = $request->input('status');
    
        // Start building the query for active users
        $userQuery = User::query();
    
        // Apply search if there is one
        if ($userSearch) {
            $userQuery->where(function ($q) use ($userSearch) {
                $q->where('first_name', 'like', "%{$userSearch}%")
                    ->orWhere('email', 'like', "%{$userSearch}%")
                    ->orWhere('role', 'like', "%{$userSearch}%");
            });
        }
    
        // Apply status filter if specified
        if ($status && $status !== 'All') {
            $userQuery->where('status', $status);
        }
    
        // Paginate the results for users
        $users = $userQuery->paginate(10);
    
        // Now query for clients from OrgAccount
        $clientQuery = OrgAccount::with('orgSetup');
    
        // Apply the search for clients if necessary
        if ($clientSearch) {
            $clientQuery->where(function ($q) use ($clientSearch) {
                $q ->where('email', 'like', "%{$clientSearch}%")
                    ->orWhereHas('orgSetup', function ($q) use ($clientSearch) {
                        $q->where('registration_name', 'like', "%{$clientSearch}%"); // Assuming this is the field in orgSetup
                    });
            });
        }
    
        // Apply any status filter for clients if applicable
        if ($status && $status !== 'All') {
            $clientQuery->where('status', $status); // Assuming OrgAccount has a 'status' field
        }
    
        // Paginate the results for clients
        $clients = $clientQuery->paginate(10);
    
        // Return view with both users and clients
        return view('user-management', compact('users', 'clients'));
    }
        

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'middle_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
        ]);
    
        // Generate a random password
        $password = Str::random(10); // Generates a random 10 character string
    
        // Create the user
        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'suffix' => $request->suffix,
            'email' => $request->email,
            'password' => bcrypt($password), // Hash the password
        ]);
    
        // Send email with credentials
        Mail::send([], [], function ($message) use ($user, $password) {
            $message->to($user->email)
                    ->subject('Your New Account Details')
                    ->html("
                        <p>Hello {$user->first_name},</p>
                        <p>Your account has been successfully created with the following details:</p>
                        <ul>
                            <li>Email: {$user->email}</li>
                            <li>Password: {$password}</li>
                        </ul>
                        <p>Please log in and change your password at your earliest convenience.</p>
                    ");
        });
    
        return redirect()->route('user-management')->with('success', "The account for {$user->first_name} {$user->last_name} has been successfully added. A generated password has been sent via email for account login.");
    }
    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->input('user_id'));
        $user->delete();
    
        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}
