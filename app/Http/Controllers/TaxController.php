<?php

namespace App\Http\Controllers;

use App\Models\Tax;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TaxController extends Controller
{
      use AuthorizesRequests;
    public function index(Request $request)
    {
        $this->authorize('user_view');
        $trashuser = Tax::onlyTrashed()->count();
        $taxes = Tax::orderBy('min_income')->get();
        return view('tax.index', compact('taxes', 'trashuser'));
    }

    public function create()
    {
        $this->authorize('user_create');
        return view('tax.create');
    }

    public function store(Request $request)
    {
         $validated = $request->validate([
            'min_income' => ['required', 'numeric', 'min:0'],
            'max_income' => ['nullable', 'numeric', 'gte:min_income'],
            'fixed_tax'  => ['required', 'numeric', 'min:0'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);
        try {
           $tax = Tax::create($validated);

            logUserActivity('Tax', 'Create Tax ' . $tax->name, $tax->id, 'Tax');

            return redirect()->route('tax.index')->with('success', 'User created successfully.');
        } catch (Exception $e) {
            Log::error('User creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating user: ' . $e->getMessage());
        }
    }
    public function edit(Tax $tax)
    {
        $this->authorize('user_edit');
       return view('tax.edit', ['slab'=>$tax]);
    }

    public function update(Request $request, Tax $tax)
    {
        $validated = $request->validate([
            'min_income' => ['required', 'numeric', 'min:0'],
            'max_income' => ['nullable', 'numeric', 'gte:min_income'],
            'fixed_tax'  => ['required', 'numeric', 'min:0'],
            'percentage' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        try {
            $tax->update($validated);

            logUserActivity('tax', 'Update tax', $tax->id, 'tax');

            return redirect()->route('tax.index')->with('success', 'Tax updated successfully.');
        } catch (\Exception $e) {
            Log::error('Tax update failed: ' . $e->getMessage());

            return redirect()->back()->withInput()->with('error', 'Error updating tax: ' . $e->getMessage());
        }
    }


    public function destroy(Tax $tax)
    {
        try {
            $tax->delete;
            logUserActivity('tax', 'Deleted tax ' . $tax->name, $tax->id, 'tax');
            $tax->delete();
            return redirect()->route('tax.index')->with('success', 'User deleted successfully.');
        } catch (Exception $e) {
            Log::error('User deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting user: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('user_trash_view');
        $tax = Tax::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('tax.trash', compact('users'));
    }

    public function restore($id)
    {
        try {
            $tax = Tax::withTrashed()->findOrFail($id);
            $tax->restore();
            return redirect()->route('tax.index')->with('success', 'tax restored successfully.');
        } catch (Exception $e) {
            Log::error('tax restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring tax: ' . $e->getMessage());
        }
    }
}
