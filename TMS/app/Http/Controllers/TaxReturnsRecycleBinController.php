<?php

namespace App\Http\Controllers;

use App\Models\TaxReturn;
use App\Models\WithHolding;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;


class TaxReturnsRecycleBinController extends Controller
{
    /**
     * Display a listing of soft-deleted tax returns and withholdings.
     */
    public function index(Request $request)
    {
        // Fetch soft-deleted TaxReturns and WithHoldings
        $taxReturns = TaxReturn::onlyTrashed()->with(['deletedByUser', 'Organization', 'user'])->get();
        $withHoldings = WithHolding::onlyTrashed()->with(['deletedByUser', 'organization', 'creator'])->get();

        // Add a model_type attribute to differentiate between the two
        $taxReturns->each(function ($item) {
            $item->model_type = 'TaxReturn';
        });

        $withHoldings->each(function ($item) {
            $item->model_type = 'WithHolding';
        });

        // Merge the data
        $mergedData = $taxReturns->merge($withHoldings);

        if ($request->has('search') && $request->search != '') {
            $search = strtolower(trim($request->search)); // Convert to lowercase and trim spaces

            Log::info('Search Parameter:', ['search' => $search]);

            $mergedData = $mergedData->filter(function ($item) use ($search) {
                // Get relevant attributes and convert them to lowercase for case-insensitive search
                $organizationName = strtolower($item->Organization->registration_name ?? $item->organization->registration_name ?? '');
                $taxType = strtolower($item->tax_type ?? $item->type ?? '');
                $title = strtolower($item->title ?? $item->type ?? '');

                // Log attributes being checked
                Log::info('Checking Item:', [
                    'item_id' => $item->id,
                    'organization_name' => $organizationName,
                    'tax_type' => $taxType,
                    'title' => $title,
                ]);

                // Check if any field contains the search term
                return (
                    str_contains($organizationName, $search) ||
                    str_contains($taxType, $search) ||
                    str_contains($title, $search)
                );
            });

            Log::info('Filtered Data Count:', ['count' => $mergedData->count()]);
        }

        // Sort the data by deleted_at
        $mergedData = $mergedData->sortByDesc('deleted_at')->values();

        // Paginate the filtered data
        $perPage = 5;
        $currentPage = $request->input('page', 1);
        $currentItems = $mergedData->slice(($currentPage - 1) * $perPage, $perPage);

        $paginatedData = new LengthAwarePaginator(
            $currentItems,
            $mergedData->count(),
            $perPage,
            $currentPage,
            ['path' => $request->url(), 'query' => $request->query()] // Ensure the URL and query parameters are preserved
        );

        return view('recycle-bin.tax-returns', [
            'trashedItems' => $paginatedData,
            'allTrashedItems' => $mergedData, // Send all items
        ]);
    }

    /**
     * Restore selected soft-deleted tax returns and withholdings.
     */
    public function bulkRestore(Request $request)
    {

        Log::info('Bulk restore request:', $request->all());

        $request->validate([
            'tax_return_ids' => 'nullable|array',
            'tax_return_ids.*' => 'integer|exists:tax_returns,id',
            'withholdings_ids' => 'nullable|array',
            'withholdings_ids.*' => 'integer|exists:withholdings,id',
        ]);

        $taxReturnIds = $request->input('tax_return_ids', []);
        $withHoldingIds = $request->input('withholdings_ids', []);

        if ($taxReturnIds) {
            TaxReturn::onlyTrashed()->whereIn('id', $taxReturnIds)->restore();
        }

        if ($withHoldingIds) {
            WithHolding::onlyTrashed()->whereIn('id', $withHoldingIds)->restore();
        }

        return response()->json(['success' => 'Selected records restored successfully.']);
    }

    /**
     * Permanently delete selected soft-deleted tax returns and withholdings.
     */
public function bulkDelete(Request $request)
{
    try {
        Log::info('Bulk Delete Request Received:', $request->all());

        // Validate the request
        $request->validate([
            'tax_return_ids' => 'nullable|array',
            'tax_return_ids.*' => 'integer|exists:tax_returns,id',
            'withholdings_ids' => 'nullable|array',
            'withholdings_ids.*' => 'integer|exists:withholdings,id',
        ]);

        $taxReturnIds = $request->input('tax_return_ids', []);
        $withHoldingIds = $request->input('withholdings_ids', []);

        Log::info('Validated Tax Return IDs:', $taxReturnIds);
        Log::info('Validated WithHolding IDs:', $withHoldingIds);

        if (!empty($taxReturnIds)) {
            $deleted = TaxReturn::onlyTrashed()->whereIn('id', $taxReturnIds)->forceDelete();
            Log::info('Tax Returns Deleted:', $deleted);
        }

        if (!empty($withHoldingIds)) {
            $deleted = WithHolding::onlyTrashed()->whereIn('id', $withHoldingIds)->forceDelete();
            Log::info('WithHoldings Deleted:', $deleted);
        }

        return response()->json(['success' => 'Selected records permanently deleted.']);
    } catch (\Exception $e) {
        Log::error('Bulk Delete Error:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        return response()->json(['error' => 'An error occurred during the deletion process.'], 500);
    }
}


}
