<?php

namespace App\Http\Controllers\Auth;

use App\Enums\UserTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
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
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = auth()->user();

        if (in_array($user->type, [UserTypeEnum::SUPER_ADMIN, UserTypeEnum::ADMIN])) {

            return response()->json([
                'redirect_to' => redirect()->intended(route('admin.home', absolute: false))->getTargetUrl(),
                'refresh' => true,
            ]);

        } elseif (in_array($user->type, [UserTypeEnum::ORGANIZATION_SUPER_ADMIN, UserTypeEnum::ORGANIZATION_ADMIN,])) {

            return response()->json([
                'redirect_to' => redirect()->intended(route('organization.home', absolute: false))->getTargetUrl(),
                'refresh' => true,
            ]);

        } elseif ($user->hasRole(UserTypeEnum::TEACHER)) {

            return response()->json([
                'redirect_to' => redirect()->intended(route('teacher.home', absolute: false))->getTargetUrl(),
                'refresh' => true,
            ]);

        } elseif ($user->hasRole(UserTypeEnum::EXAMINER)) {

            return response()->json([
                'redirect_to' => redirect()->intended(route('examiner.home', absolute: false))->getTargetUrl(),
                'refresh' => true,
            ]);

        } else {

            return response()->json([
                'redirect_to' => redirect()->intended(route('welcome', absolute: false))->getTargetUrl(),
                'refresh' => true,
            ]);

        }

    }//end of store

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
