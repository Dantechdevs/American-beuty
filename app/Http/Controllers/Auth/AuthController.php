<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\ActivityLogService;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;


class AuthController extends Controller
{
    public function showLogin()
    {
        return Auth::check() ? redirect()->route('home') : view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $user = auth()->user();

            // ── Block suspended accounts ───────────────────────
            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors(['email' => 'Your account has been suspended.']);
            }

            $request->session()->regenerate();

            // ── Log the login event ────────────────────────────
            ActivityLogService::login($user);

            // ── Redirect based on role ─────────────────────────
            return $this->redirectAfterLogin($user, $request);
        }

        return back()
            ->withErrors(['email' => 'Invalid email or password.'])
            ->withInput($request->only('email'));
    }

    public function showRegister()
    {
        return Auth::check() ? redirect()->route('home') : view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users',
            'phone'    => 'nullable|string|max:20',
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'phone'    => $data['phone'] ?? null,
            'password' => Hash::make($data['password']),
            'role'     => 'customer',
        ]);

        Auth::login($user);
        $request->session()->regenerate();
        app(CartService::class)->mergeSessionCart();

        // ── Log registration ───────────────────────────────────
        ActivityLogService::login($user);

        return redirect()->route('home')
            ->with('success', 'Account created! Welcome to American Beauty.');
    }

    public function logout(Request $request)
    {
        // ── Log logout event ───────────────────────────────────
        if (Auth::check()) {
            ActivityLogService::logout(Auth::user());
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Logged out successfully.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ── Role-based redirect ────────────────────────────────────
    private function redirectAfterLogin(User $user, Request $request): \Illuminate\Http\RedirectResponse
    {
        return match($user->role) {
            'admin'        => redirect()->route('admin.dashboard'),
            'manager'      => redirect()->route('admin.dashboard'),
            'pos_operator' => redirect()->route('admin.pos.index'),
            'delivery'     => redirect()->route('admin.orders.index'),
            default        => tap(
                redirect()->intended(route('home'))
                    ->with('success', 'Welcome back, ' . $user->name . '!'),
                fn() => app(CartService::class)->mergeSessionCart()
            ),
        };
    }
}