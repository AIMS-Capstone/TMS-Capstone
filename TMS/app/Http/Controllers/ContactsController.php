<?php

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class ContactsController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('contact_type');
        $classification = $request->input('classification');
        $perPage = $request->input('perPage', 5);
        $organizationId = session('organization_id');

        $query = Contacts::query()->where('organization_id', $organizationId);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('bus_name', 'like', "%{$search}%")
                    ->orWhere('contact_tin', 'like', "%{$search}%")
                    ->orWhere('contact_city', 'like', "%{$search}%");
            });
        }

        if ($type && $type !== 'All') {
            $query->where('contact_type', $type);
        }

        if ($classification && $classification !== 'All') {
            $query->where('classification', $classification);
        }

        $contacts = $query->paginate($perPage);

        return view('contacts', compact('contacts'));
    }

    public function store(Request $request)
    {
        $organizationId = session('organization_id');

        if (!$organizationId) {
            return redirect()->back()->withErrors(['error' => 'User does not have an organization ID.']);
        }

        $validated = $request->validate([
            'contact_type' => 'required|string',
            'classification' => 'required|string',
            'bus_name' => 'required|string|max:50',
            'contact_tin' => 'nullable|string|max:17',
            'contact_address' => 'nullable|string|max:50',
            'contact_city' => 'required|string|max:20',
            'contact_zip' => 'required|string|max:4',
        ]);

        try {
            $validated['organization_id'] = $organizationId;
            $contact = Contacts::create($validated);

            // Log creation
            activity('contacts')
                ->performedOn($contact)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $organizationId,
                    'attributes' => $contact->toArray(),
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Contact {$contact->bus_name} created");

            return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create contact.']);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'contact_type' => 'required|string',
            'classification' => 'required|string',
            'bus_name' => 'required|string|max:50',
            'contact_tin' => 'nullable|string|max:17',
            'contact_address' => 'nullable|string|max:50',
            'contact_city' => 'required|string|max:20',
            'contact_zip' => 'required|string|max:4',
        ]);

        try {
            $contact = Contacts::findOrFail($id);
            $oldAttributes = $contact->getOriginal();

            // Perform the update
            $contact->update($validated);

            // Get changes after update
            $changedAttributes = $contact->getChanges();

            if (!empty($changedAttributes)) {
                $changes = [];
                foreach ($changedAttributes as $key => $newValue) {
                    $changes[$key] = [
                        'old' => $oldAttributes[$key] ?? 'N/A',
                        'new' => $newValue,
                    ];
                }

                activity('contacts')
                    ->performedOn($contact)
                    ->causedBy(Auth::user())
                    ->withProperties([
                        'organization_id' => $contact->organization_id,
                        'changes' => $changes,
                        'ip' => request()->ip(),
                        'browser' => request()->header('User-Agent'),
                    ])
                    ->log("Contact {$contact->bus_name} updated");
            }

            return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update contact.']);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id',
        ]);

        $contacts = Contacts::whereIn('id', $request->ids)->get();

        foreach ($contacts as $contact) {
            $contact->delete();

            // Log deletion
            activity('contacts')
                ->performedOn($contact)
                ->causedBy(Auth::user())
                ->withProperties([
                    'organization_id' => $contact->organization_id,
                    'ip' => request()->ip(),
                    'browser' => request()->header('User-Agent'),
                ])
                ->log("Contact {$contact->bus_name} was deleted");
        }

        return response()->json(['success' => true, 'message' => 'Contacts deleted successfully.']);
    }
}
