<?php

namespace App\Http\Controllers;

use App\Models\ExitEntryRequest;
use App\Notifications\ExitEntryRequestSubmitted;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExitEntryRequestController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $requests = ExitEntryRequest::whereHas('employee', function ($query) use ($user) {
            $query->whereIn('user_id', $user->subordinates->pluck('id'));
        })->latest()->paginate(10);

        return view('employee-portal.exit-entry.index', compact('requests'));
    }

    public function create()
    {
        return view('employee-portal.exit-entry.create');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $employee = $user->employee;

        if (!$employee) {
            return redirect()->route('employee-portal.index')->with('error', __('No employee profile found for your account. Please contact HR to set up your employee profile.'));
        }

        $validated = $request->validate([
            'exit_date' => 'required|date|after:today',
            'return_date' => 'required|date|after:exit_date',
            'reason' => 'required|string|max:255',
        ]);

        $managerId = $user->manager_id;
        if (!$managerId) {
            return redirect()->route('employee-portal.index')->with('error', __('You do not have a manager assigned. Please contact HR.'));
        }

        $exitEntryRequest = ExitEntryRequest::create([
            'employee_id' => $employee->id,
            'exit_date' => $validated['exit_date'],
            're_entry_date' => $validated['return_date'],
            'reason' => $validated['reason'],
            'status' => 'pending',
        ]);

        // Notify the manager
        $manager = $user->manager;
        if ($manager) {
            $manager->notify(new ExitEntryRequestSubmitted($exitEntryRequest));
        }

        return redirect()->route('employee-portal.index')->with('success', __('Exit/Entry Request submitted successfully.'));
    }

    public function approve(ExitEntryRequest $request)
    {
        $user = Auth::user();
        if (!$request->employee->user->manager || $request->employee->user->manager->id !== $user->id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $request->update(['status' => 'approved']);
        return redirect()->route('manager-portal.index')->with('success', __('Exit/Entry Request approved successfully.'));
    }

    public function reject(ExitEntryRequest $request)
    {
        $user = Auth::user();
        if (!$request->employee->user->manager || $request->employee->user->manager->id !== $user->id) {
            return redirect()->route('manager-portal.index')->with('error', __('You are not authorized to perform this action.'));
        }

        $request->update(['status' => 'rejected']);
        return redirect()->route('manager-portal.index')->with('success', __('Exit/Entry Request rejected successfully.'));
    }
}