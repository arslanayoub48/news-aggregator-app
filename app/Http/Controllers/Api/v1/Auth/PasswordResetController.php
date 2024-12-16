<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\PasswordResetRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * @OA\Post(
     *      path="/password-reset",
     *      summary="Request password reset link",
     *      description="Send a password reset link to the user's email",
     *      tags={"Password Reset"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email"},
     *              @OA\Property(property="email", type="string", example="john@example.com")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Password reset link sent"),
     *      @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function requestPasswordReset(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink($request->only('email'));

        if ($status == Password::RESET_LINK_SENT) {
            return $this->singleSuccessResponse('Password reset link sent to your email.');
        }

        return $this->failure('Unable to send reset link. Please check your email address.');
    }


    /**
     * @OA\Post(
     *      path="/password-reset/confirm",
     *      summary="Reset user password",
     *      description="Reset password using a valid token",
     *      tags={"Password Reset"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "token", "password", "password_confirmation"},
     *              @OA\Property(property="token", type="string", example="abcdef123456"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="password", type="string", example="NewPassword123"),
     *              @OA\Property(property="password_confirmation", type="string", example="NewPassword123")
     *          )
     *      ),
     *      @OA\Response(response=200, description="Password reset successfully"),
     *      @OA\Response(response=422, description="Validation errors")
     * )
     */

    public function resetPassword(PasswordResetRequest $request)
    {
        $request->validated();

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return $this->singleSuccessResponse('Password has been reset successfully.');
        }

        return $this->failure('Failed to reset password.');
    }
}
