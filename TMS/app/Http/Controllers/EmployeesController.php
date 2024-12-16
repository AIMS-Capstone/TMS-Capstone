<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Employee;
use App\Models\Employment;
use Illuminate\Http\Request;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel as MaatExcel;


class EmployeesController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('perPage', 5);

        $organizationId = session('organization_id');

        // Query employees with their address and employments
        $query = Employee::with(['address', 'employments']);

        if ($organizationId) {
            $query->where('organization_id', $organizationId);
        }

        // Search functionality
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%")
                    ->orWhere('tin', 'like', "%$search%");
            });
        }

        // Paginate results
        $employees = $query->paginate($perPage);

        return view('employees', compact('employees'));
    }

    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validated = $request->validate([
                // Employee details
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'date_of_birth' => 'required|date',
                'tin' => 'required|string|max:17|unique:employees,tin',
                'nationality' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:15',

                // Employee address
                'address' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',

                // Employer details
                'employer_name' => 'required|string|max:255',
                'employment_from' => 'required|date',
                'employment_to' => 'nullable|date|after_or_equal:employment_from',
                'rate' => 'required|numeric',
                'rate_per_month' => 'required|numeric',
                'employment_status' => 'required|string',
                'reason_for_separation' => 'nullable|string|max:255',
                'employee_wage_status' => 'required|string',
                'previous_employer_tin' => 'required|string',
                'substituted_filing' => 'required|boolean',
                'prev_employment_from' => 'required|date',
                'prev_employment_to' => 'nullable|date|after_or_equal:prev_employment_from',
                'prev_employment_status' => 'nullable|string',
                'with_prev_employer' => 'required|boolean',

                // Employer address
                'prev_address' => 'required|string|max:255',
                'prev_zip_code' => 'required|string|max:10',
                'region' => 'required|string|max:255',
            ]);

            // Step 1: Store Employee
            $employee = Employee::create([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'],
                'date_of_birth' => $validated['date_of_birth'],
                'tin' => $validated['tin'],
                'nationality' => $validated['nationality'],
                'contact_number' => $validated['contact_number'],
                'organization_id' => session('organization_id'),
            ]);

            // Step 2: Assign Address to Employee
            $employee->address()->create([
                'address' => $validated['address'],
                'zip_code' => $validated['zip_code'],
            ]);

            // Step 3: Store Employer Details and Address
            $employment = Employment::create([
                'employer_name' => $validated['employer_name'],
                'employment_from' => $validated['employment_from'],
                'employment_to' => $validated['employment_to'],
                'rate' => $validated['rate'],
                'rate_per_month' => $validated['rate_per_month'],
                'employment_status' => $validated['employment_status'],
                'reason_for_separation' => $validated['reason_for_separation'],
                'previous_employer_tin' => $validated['previous_employer_tin'],
                'prev_employment_from' => $validated['prev_employment_from'],
                'prev_employment_to' => $validated['prev_employment_to'],
                'employee_wage_status' => $validated['employee_wage_status'],
                'substituted_filing' => $validated['substituted_filing'],
                'prev_employment_status' => $validated['prev_employment_status'],
                'employee_id' => $employee->id,
            ]);

            // Assign Address to Employer
            $employment->address()->create([
                'address' => $validated['prev_address'],
                'zip_code' => $validated['prev_zip_code'],
                'region' => $validated['region'],
            ]);

            return redirect()->route('employees')->with('success', 'Employee and Employer addresses added successfully!');
        } catch (\Exception $e) {
            // pang debug lang dami kasing data hatdog
            dd($e->getMessage() );
        }
    }

    public function update(Request $request, $id)
    {
        try {
            // Step 1: Validate the request data
            $validated = $request->validate([
                // Employee details
                'first_name' => 'required|string|max:255',
                'middle_name' => 'nullable|string|max:255',
                'last_name' => 'required|string|max:255',
                'suffix' => 'nullable|string|max:10',
                'date_of_birth' => 'required|date',
                'tin' => 'required|string|max:17|unique:employees,tin,' . $id,
                'nationality' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:15',

                // Employee address
                'address' => 'required|string|max:255',
                'zip_code' => 'required|string|max:10',

                // Employer details
                'employer_name' => 'required|string|max:255',
                'employment_from' => 'required|date',
                'employment_to' => 'nullable|date|after_or_equal:employment_from',
                'rate' => 'required|numeric',
                'rate_per_month' => 'required|numeric',
                'employment_status' => 'required|string',
                'reason_for_separation' => 'nullable|string|max:255',
                'employee_wage_status' => 'required|string',
                'substituted_filing' => 'required|boolean',

                // Employer address
                'prev_address' => 'required|string|max:255',
                'prev_zip_code' => 'required|string|max:10',
                'region' => 'required|string|max:255',
            ]);

            // Step 2: Find the employee by ID
            $employee = Employee::with(['address', 'employments.address'])->findOrFail($id);

            // Step 3: Update the employee details
            $employee->update([
                'first_name' => $validated['first_name'],
                'middle_name' => $validated['middle_name'],
                'last_name' => $validated['last_name'],
                'suffix' => $validated['suffix'],
                'date_of_birth' => $validated['date_of_birth'],
                'tin' => $validated['tin'],
                'nationality' => $validated['nationality'],
                'contact_number' => $validated['contact_number'],
            ]);

            // Step 4: Update or create the employee's address
            $employee->address()->updateOrCreate([], [
                'address' => $validated['address'],
                'zip_code' => $validated['zip_code'],
            ]);

            // Step 5: Update the employment details
            if ($employee->employments->isEmpty()) {
                // Create new employment if none exists
                $employment = $employee->employments()->create([
                    'employer_name' => $validated['employer_name'],
                    'employment_from' => $validated['employment_from'],
                    'employment_to' => $validated['employment_to'],
                    'rate' => $validated['rate'],
                    'rate_per_month' => $validated['rate_per_month'],
                    'employment_status' => $validated['employment_status'],
                    'reason_for_separation' => $validated['reason_for_separation'],
                    'employee_wage_status' => $validated['employee_wage_status'],
                    'substituted_filing' => $validated['substituted_filing'],
                ]);
            } else {
                // Update the first employment record (or adapt logic if multiple employments are supported)
                $employment = $employee->employments->first();
                $employment->update([
                    'employer_name' => $validated['employer_name'],
                    'employment_from' => $validated['employment_from'],
                    'employment_to' => $validated['employment_to'],
                    'rate' => $validated['rate'],
                    'rate_per_month' => $validated['rate_per_month'],
                    'employment_status' => $validated['employment_status'],
                    'reason_for_separation' => $validated['reason_for_separation'],
                    'employee_wage_status' => $validated['employee_wage_status'],
                    'substituted_filing' => $validated['substituted_filing'],
                ]);
            }

            // Step 6: Update or create the employer address
            $employment->address()->updateOrCreate([], [
                'address' => $validated['prev_address'],
                'zip_code' => $validated['prev_zip_code'],
                'region' => $validated['region'],
            ]);
            
            return redirect()->route('employees')->with('success', 'Employee updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update employee: ' . $e->getMessage()]);
        }
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:employees,id',
        ]);

        // Delete employees and their associated addresses
        Employee::whereIn('id', $request->ids)->each(function ($employee) {
            $employee->address()->delete(); // Deletes the associated address
            $employee->delete(); // Deletes the employee
        });

        return response()->json(['success' => true, 'message' => 'Employees deleted successfully.']);
    }

    public function import(Request $request)
    {
        // Validate the file input
        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        // Import the file
        MaatExcel::import(new EmployeesImport, $request->file('file'));

        return back()->with('success', 'Employees data imported successfully!');
    }

}
