<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
   public function store(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (! Auth::attempt($request->only('email', 'password'), $request->filled('remember'))) {
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    $request->session()->regenerate();

    $user = Auth::user();

    switch ($user->role) {
        case 'superuser':
            return redirect()->route('superuser.home');
        case 'admin':
            return redirect()->route('admin.home');
        case 'waiter':
            return redirect()->route('waiter.home');
        case 'kitchen':
            return redirect()->route('kitchen.home');
        case 'bar':
            return redirect()->route('bar.home');
        default:
            return redirect('/'); // fallback
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
