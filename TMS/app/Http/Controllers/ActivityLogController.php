<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrgSetup;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function audit_log(Request $request)
    {
        $perPage = $request->input('perPage', 5);
        // Fetch activities with filters
        $activities = Activity::query()
            ->with('causer') // Load related user data
            ->when($request->search, function ($query) use ($request) {
                $query->where('description', 'like', '%' . $request->search . '%') // Search in activity description
                    ->orWhereHas('causer', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%'); // Search in user name
                    });
            })
            ->when($request->filter, function ($query) use ($request) {
                switch ($request->filter) {
                    case 'user':
                        $query->whereHas('causer', function ($q) use ($request) {
                            $q->where('role', 'user'); // Example: Filtering users by role
                        });
                        break;
                    case 'page':
                        $query->where('log_name', 'like', '%' . $request->search . '%');
                        break;
                    case 'activity':
                        $query->where('description', 'like', '%' . $request->search . '%');
                        break;
                    case 'ip':
                        $query->where('properties->ip', 'like', '%' . $request->search . '%');
                        break;
                    case 'browser':
                        $query->where('properties->browser', 'like', '%' . $request->search . '%');
                        break;
                }
            })
            ->when($request->organization_id, function ($query) use ($request) {
                $query->where('properties->organization_id', $request->organization_id);
            })
            ->latest()
            ->paginate($perPage);

        // Fetch all organizations for the dropdown filter
        $organizations = OrgSetup::all();

        return view('audit_log.index', compact('activities', 'organizations'));
    }
}
