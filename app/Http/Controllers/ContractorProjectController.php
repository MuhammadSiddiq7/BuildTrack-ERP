<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractorProjectController extends Controller
{
    use AuthorizesRequests;
    public function index()
    {
        $this->authorize('contractorProject_view');
        $projects = Project::with('contractors')->orderBy('created_at', 'desc')->get();
        return view('contractor.contractorProject', compact('projects'));
    }
    public function toggleStatus(Request $request)
    {
        $this->authorize('contractorProject_status');

        $request->validate([
            'contractor_id' => 'required|exists:contractors,id',
            'project_id' => 'required|exists:projects,id',
            'house_project_id' => 'required',
        ]);

        $contractorId = $request->contractor_id;
        $projectId = $request->project_id;
        $houseProjectId = $request->house_project_id;

        $pivotRow = DB::table('contractor_project')
            ->where('contractor_id', $contractorId)->where('project_id', $projectId)
            ->where('house_project_id', $houseProjectId)->first();

        if (!$pivotRow) {
            return response()->json(['success' => false, 'message' => 'Pivot record not found.'], 404);
        }

        $newStatus = $pivotRow->status === 'active' ? 'inactive' : 'active';

        DB::table('contractor_project')->where('id', $pivotRow->id)->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'new_status' => $newStatus,
        ]);
    }
}
