<?php

namespace App\Http\Controllers;

use App\Models\coa;
use App\Http\Requests\StorecoaRequest;
use App\Http\Requests\UpdatecoaRequest;
use Illuminate\Http\Request;

class CoaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $search = $request->input('search');
        $type = $request->input('type');
        
        $query = coa::query();
        
        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('code', 'like', "%{$search}%")
                ->orWhere('type', 'like', "%{$search}%");
        }
        
        if ($type && $type !== 'All') {
            $query->where('type', $type);
        }

        $coas = $query->paginate(4);

        return view('coa', compact('coas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate incoming request data
        $request->validate([
            'type' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'name' => 'required|string|max:150',
            'description' => 'nullable|string|max:255',
        ]);

        // Create new CoA record
        Coa::create([
            'type' => $request->input('type'),
            'code' => $request->input('code'),
            'name' => $request->input('name'),
            'description' => $request->input('description'),
        ]);

        // Return a response (you can customize this based on your needs)
        return redirect()->route('coa')->with('success', 'Account created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(coa $coa)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(coa $coa)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecoaRequest $request, coa $coa)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(coa $coa)
    {
        //
    }
}
