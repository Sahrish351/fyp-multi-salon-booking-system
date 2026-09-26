<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    public function showForm(string $token)
    {
        return view('auth.reset-password', ['token' => $token]);
    }

    public function reset(ResetPasswordRequest $request)
    {
        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill(['password' => Hash::make($password)])
                     ->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            
            $user = User::where('email', $request->email)->first();

            $loginRoute = match ($user->role ?? 'client') {
                'admin' => 'admin.login.form',
                'owner' => 'owner.login.form',
                default => 'client.login.form',
            };

            return redirect()->route($loginRoute)
                ->with('success', 'Your password has been reset successfully! Please login with your new password.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }
}