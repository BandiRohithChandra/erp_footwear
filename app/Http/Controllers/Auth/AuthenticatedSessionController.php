<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
public function store(LoginRequest $request): RedirectResponse
{
    try {
        // Authenticate user
        $request->authenticate();
        $request->session()->regenerate();

        // Remove any previous intended URL to prevent default /dashboard redirect
        $request->session()->forget('url.intended');

        $user = Auth::user();
        if (!$user) {
            \Log::warning('Auth::user() returned null after authentication', $request->all());
            return back()->withErrors([
                'email' => 'Login failed. Please try again.'
            ]);
        }

        // ===== Role Checks =====
        $roles = $user->roles->pluck('name')->map(fn($r) => strtolower(trim($r)))->toArray();
        $isAdmin = in_array('admin', $roles);
        $isOfflineAdmin = $isAdmin && intval($user->is_remote) === 0;
        $isClient = in_array('client', $roles);
        $isSales = in_array('sales manager', $roles) || in_array('sales employee', $roles);

        // ===== Offline Admin Environment Check =====
        $offlineInstalled = file_exists(storage_path('offline_installed'));
        if ($isOfflineAdmin && !$offlineInstalled) {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Offline admins can only log in on installed systems.'
            ]);
        }

        // Store offline admin flag in session
        session(['is_offline_admin' => $isOfflineAdmin]);

        // ===== Redirect Logic =====
        if ($isAdmin) {
            // Explicit redirect for offline and online admins
            return $isOfflineAdmin
                ? redirect()->route('products.index') // Offline admin
                : redirect()->route('admin.online'); // Online admin
        }

        if ($isClient) {
            switch ($user->status) {
                case 'pending':
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your account is pending approval. Please wait for admin approval.'
                    ]);
                case 'rejected':
                    Auth::logout();
                    return back()->withErrors([
                        'email' => 'Your account registration was rejected. Contact admin for details.'
                    ]);
                default:
                    return redirect()->route('client.dashboard'); // Approved client
            }
        }

        if ($isSales) {
            return redirect()->route('sales.dashboard');
        }

        // ===== No Recognized Role =====
        \Log::warning('User has no recognized role', [
            'user_id' => $user->id,
            'roles' => $roles,
            'is_remote' => $user->is_remote
        ]);
        Auth::logout();
        return back()->withErrors([
            'email' => 'Your account role is not recognized. Contact admin.'
        ]);

    } catch (\Exception $e) {
        \Log::error('Login exception: ' . $e->getMessage(), $request->all());
        return back()->withErrors([
            'email' => 'Login error. Please try again.'
        ]);
    }
}


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
