<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrandController extends Controller
{
     use AuthorizesRequests;
    public function index()
    {
        $this->authorize('brand_view');
        $trashBrand = Brand::onlyTrashed()->count();
        $brands = Brand::orderBy('created_at', 'desc')->get();
        return view('brand.index', compact('brands', 'trashBrand'));
    }

    public function create()
    {
        $this->authorize('brand_create');
        return view('brand.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'required',
            'cnic' => 'nullable',
            'contact_number' => 'nullable',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $brand = new Brand();
            logUserActivity('Brand', 'Create Brand ' . $brand->name, $brand->id, 'Brand');
            $brand->name = $request->name;
            $brand->owner_name = $request->owner_name;
            $brand->cnic = $request->cnic;
            $brand->contact_number = $request->contact_number;
            $brand->description = $request->description;
            $brand->status = $request->status;
            // dd($brand);
            $brand->save();

            return redirect()->route('brand.index')->with('success', 'Brand created successfully.');
        } catch (Exception $e) {
            Log::error('brand creation failed: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Error creating Brand: ' . $e->getMessage());
        }
    }
    public function edit($id)
    {
        $this->authorize('brand_edit');
        $brand = Brand::findOrFail($id);
        return view('brand.edit', compact('brand'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'owner_name' => 'nullable',
            'cnic' => 'nullable',
            'contact_number' => 'nullable',
            'description' => 'nullable',
            'status' => 'required|in:active,inactive',
        ]);
        try {
            $brand = Brand::findOrFail($id);
            logUserActivity('Brand', 'Update Brand ' . $brand->name, $brand->id, 'Brand');
            $brand->name = $request->name;
            $brand->owner_name = $request->owner_name;
            $brand->cnic = $request->cnic;
            $brand->contact_number = $request->contact_number;
            $brand->description = $request->description;
            $brand->status = $request->status;
            $brand->save();

            return redirect()->route('brand.index')->with('success', 'Brand updated successfully.');
        } catch (Exception $e) {
            Log::error('brand update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating Brand: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $brand = Brand::findOrFail($id);
            logUserActivity('Brand', 'Delete Brand ' . $brand->name, $brand->id, 'Brand');
            $brand->delete();
            return redirect()->route('brand.index')->with('success', 'Brand deleted successfully.');
        } catch (Exception $e) {
            Log::error('brand deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting Brand: ' . $e->getMessage());
        }
    }

    public function trash()
    {
        $this->authorize('brand_trash_view');
        $brands = Brand::onlyTrashed()->orderBy('created_at', 'desc')->get();
        return view('brand.trash', compact('brands'));
    }

    public function restore($id)
    {
        try {
            $brand = Brand::withTrashed()->findOrFail($id);
            $brand->restore();
            return redirect()->route('brand.index')->with('success', 'Brand restored successfully.');
        } catch (Exception $e) {
            Log::error('brand restoration failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error restoring Brand: ' . $e->getMessage());
        }
    }
}
