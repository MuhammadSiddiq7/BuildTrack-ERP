<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use Exception;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class HolidayController extends Controller
{
     use AuthorizesRequests;
    public function index()
    {
        $this->authorize('holiday_view');
        $holidays = Holiday::orderBy('created_at', 'desc')->get();
        return view('holiday.index', compact('holidays'));
    }

    public function create()
    {
        $this->authorize('holiday_create');
        return view('holiday.create');
    }

            public function store(Request $request)
        {
            $request->validate([
                'title'       => 'required|string|max:255',
                'type'        => 'required|in:weekend,holiday',
                'date'        => 'nullable|date',         // single date
                'start_date'  => 'nullable|date',
                'end_date'    => 'nullable|date|after_or_equal:start_date',
                'description' => 'nullable|string',
            ]);
            // dd($request->all());
            try {
                if ($request->type === 'weekend') {
                    $date = \Carbon\Carbon::parse($request->date);

                    $holiday = Holiday::create([
                        'title'       => $request->title,
                        'date'        => $date->toDateString(),
                        'type'        => 'weekend',
                        'description' => $request->description,
                        'is_recurring'=> true,
                        'day_of_week' => $date->dayOfWeek,
                        'status' =>  $request->status,
                    ]);

                    logUserActivity('holiday', 'Marked weekend: ' . $holiday->title, $holiday->id, 'holiday');
                    return redirect()->route('holiday.index')->with('success', 'Weekend marked for every year successfully!');
                }

                // ✅ Holiday (single date or range)
                $holiday = Holiday::create([
                    'title'       => $request->title,
                    'type'        => 'holiday',
                    'date'        => $request->date,
                    'start_date'  => $request->start_date,
                    'end_date'    => $request->end_date,
                    'description' => $request->description,
                    'is_recurring'=> false,
                    'day_of_week' => null,
                    'status' =>  $request->status,
                ]);

                logUserActivity('holiday', 'Create holiday ' . $holiday->title, $holiday->id, 'holiday');

                return redirect()->route('holiday.index')->with('success', 'Holiday created successfully.');
            } catch (Exception $e) {
                Log::error('holiday creation failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Error creating holiday: ' . $e->getMessage());
            }
        }

    public function edit($id)
    {
        $this->authorize('holiday_edit');
        $holiday = Holiday::findOrFail($id);
        return view('holiday.edit', compact('holiday'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'nullable',
            'type' => 'required|in:weekend,holiday',
            'description' => 'nullable|string',
        ]);
        try {
            $holiday = holiday::findOrFail($id);
            logUserActivity('holiday', 'Update holiday ' . $holiday->name, $holiday->id, 'holiday');
            $holiday->title = $request->title;
            $holiday->date = $request->date;
            $holiday->type = $request->type;
            $holiday->description = $request->description;
            $holiday->status = $request->status;
            $holiday->save();
            return redirect()->route('holiday.index')->with('success', 'holiday updated successfully.');
        } catch (Exception $e) {
            Log::error('holiday update failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error updating holiday: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $holiday = holiday::findOrFail($id);
            logUserActivity('holiday', 'Deleted holiday ' . $holiday->name, $holiday->id, 'holiday');
            $holiday->delete();
            return redirect()->route('holiday.index')->with('success', 'holiday deleted successfully.');
        } catch (Exception $e) {
            Log::error('holiday deletion failed: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error deleting holiday: ' . $e->getMessage());
        }
    }


}
