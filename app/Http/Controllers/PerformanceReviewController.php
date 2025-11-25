<?php

namespace App\Http\Controllers;

use App\Models\PerformanceReview;
use App\Models\WarningLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PerformanceReviewController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $employee = $user->employee;

        // For employees: view their own performance reviews
        $employeeReviews = [];
        if ($employee) {
            $employeeReviews = PerformanceReview::where('employee_id', $employee->id)
                ->with('reviewer')
                ->latest()
                ->get();
        }

        // For managers: view performance reviews they need to conduct
        $managerReviews = [];
        if ($user->hasRole('manager')) {
            $managerReviews = PerformanceReview::where('reviewer_id', $user->id)
                ->with('employee')
                ->latest()
                ->get();
        }

        return view('performance-reviews.index', compact('employeeReviews', 'managerReviews'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!$user->hasRole('manager') && !$user->hasRole('hr')) {
            return redirect()->route('performance-reviews.index')->with('error', __('You are not authorized to schedule performance reviews.'));
        }

        // Fetch employees under this manager
        $employees = $user->hasRole('manager') 
            ? Employee::whereIn('user_id', $user->subordinates->pluck('id'))->get()
            : Employee::all();

        return view('performance-reviews.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasRole('manager') && !$user->hasRole('hr')) {
            return redirect()->route('performance-reviews.index')->with('error', __('You are not authorized to schedule performance reviews.'));
        }

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'review_date' => 'required|date|after:today',
        ]);

        PerformanceReview::create([
            'employee_id' => $validated['employee_id'],
            'reviewer_id' => $user->id,
            'review_date' => $validated['review_date'],
            'status' => 'scheduled',
        ]);

        return redirect()->route('performance-reviews.index')->with('success', __('Performance review scheduled successfully.'));
    }

    public function show(PerformanceReview $performanceReview)
    {
        $user = Auth::user();
        $employee = $user->employee;

        // Authorization: employees can view their own reviews, managers/HR can view reviews they conducted
        if (($employee && $performanceReview->employee_id !== $employee->id) && 
            ($performanceReview->reviewer_id !== $user->id && !$user->hasRole('hr'))) {
            return redirect()->route('performance-reviews.index')->with('error', __('You are not authorized to view this performance review.'));
        }

        // Fetch related warning letters for context
        $warningLetters = WarningLetter::where('employee_id', $performanceReview->employee_id)
            ->latest()
            ->take(5)
            ->get();

        return view('performance-reviews.show', compact('performanceReview', 'warningLetters'));
    }

    public function complete(Request $request, PerformanceReview $performanceReview)
    {
        $user = Auth::user();
        if ($performanceReview->reviewer_id !== $user->id && !$user->hasRole('hr')) {
            return redirect()->route('performance-reviews.index')->with('error', __('You are not authorized to complete this performance review.'));
        }

        if ($performanceReview->status !== 'scheduled') {
            return redirect()->route('performance-reviews.index')->with('error', __('This performance review cannot be completed.'));
        }

        $validated = $request->validate([
            'feedback' => 'required|string',
            'rating' => 'required|integer|between:1,5',
            'goals' => 'required|string',
        ]);

        $performanceReview->update([
            'feedback' => $validated['feedback'],
            'rating' => $validated['rating'],
            'goals' => $validated['goals'],
            'status' => 'completed',
        ]);

        return redirect()->route('performance-reviews.index')->with('success', __('Performance review completed successfully.'));
    }

    public function cancel(PerformanceReview $performanceReview)
    {
        $user = Auth::user();
        if ($performanceReview->reviewer_id !== $user->id && !$user->hasRole('hr')) {
            return redirect()->route('performance-reviews.index')->with('error', __('You are not authorized to cancel this performance review.'));
        }

        if ($performanceReview->status !== 'scheduled') {
            return redirect()->route('performance-reviews.index')->with('error', __('This performance review cannot be cancelled.'));
        }

        $performanceReview->update(['status' => 'cancelled']);

        return redirect()->route('performance-reviews.index')->with('success', __('Performance review cancelled successfully.'));
    }
}