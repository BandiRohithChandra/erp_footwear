<?php

namespace App\Http\Controllers;

use App\Models\Employee; // Add this import
use App\Models\WarningLetter;
use App\Notifications\WarningLetterIssued;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WarningLetterController extends Controller
{
    public function index()
    {
        $warningLetters = WarningLetter::latest()->paginate(10);
        $employees = Employee::all(); // Ensure this works after adding the import
        return view('hr.warning-letters.index', compact('warningLetters', 'employees'));
    }

    public function create()
    {
        $employees = Employee::all();
        return view('hr.warning-letters.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id', // Updated to reference employees table
            'reason' => 'required|string|max:255',
            'description' => 'nullable|string',
            'issue_date' => 'required|date',
        ]);

        $employee = Employee::findOrFail($validated['employee_id']);
        if (!$employee->user) {
            return redirect()->back()->with('error', __('Employee does not have a corresponding user account.'));
        }

        $warningLetter = WarningLetter::create([
            'employee_id' => $validated['employee_id'],
            'issuer_id' => auth()->id(),
            'reason' => $validated['reason'],
            'description' => $validated['description'],
            'issue_date' => $validated['issue_date'],
            'status' => 'issued',
        ]);

        $employee->user->notify(new WarningLetterIssued($warningLetter));

        return redirect()->route('warning-letters.index')->with('success', __('Warning letter issued successfully.'));
    }

    public function upload(Request $request, WarningLetter $warningLetter)
    {
        $request->validate([
            'signed_letter' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $path = $request->file('signed_letter')->store('warning-letters', 'public');
        $warningLetter->update([
            'file_path' => $path,
            'status' => 'uploaded',
        ]);

        return redirect()->route('warning-letters.index')->with('success', __('Signed letter uploaded successfully.'));
    }

    public function print(WarningLetter $warningLetter)
    {
        return view('hr.warning-letters.print', compact('warningLetter'));
    }
}