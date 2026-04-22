<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shift;
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    public function index()
    {
        $shifts = Shift::withCount('employees')->latest()->get();
        return view('admin.attendance.shifts', compact('shifts'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:100|unique:shifts,name',
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'grace_minutes' => 'nullable|integer|min:0|max:120',
            'is_active'     => 'nullable|boolean',
        ]);

        Shift::create([
            'name'          => $request->name,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'grace_minutes' => $request->grace_minutes ?? 15,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Shift "' . $request->name . '" created successfully.');
    }

    public function update(Request $request, Shift $shift)
    {
        $request->validate([
            'name'          => 'required|string|max:100|unique:shifts,name,' . $shift->id,
            'start_time'    => 'required|date_format:H:i',
            'end_time'      => 'required|date_format:H:i',
            'grace_minutes' => 'nullable|integer|min:0|max:120',
            'is_active'     => 'nullable|boolean',
        ]);

        $shift->update([
            'name'          => $request->name,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'grace_minutes' => $request->grace_minutes ?? 15,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return back()->with('success', 'Shift "' . $shift->name . '" updated successfully.');
    }

    public function destroy(Shift $shift)
    {
        if ($shift->employees()->count() > 0) {
            return back()->with('error', 'Cannot delete shift — ' . $shift->employees_count . ' employees are assigned to it.');
        }

        $name = $shift->name;
        $shift->delete();
        return back()->with('success', 'Shift "' . $name . '" deleted.');
    }
}
