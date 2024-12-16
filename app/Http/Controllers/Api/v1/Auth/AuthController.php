<?php

namespace App\Http\Controllers\Api\v1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Resources\UserResource;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }
    /**
     * @OA\Post(
     *      path="/register",
     *      summary="Register a new user",
     *      description="Register a user by providing name, email, and password",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name", "email", "password", "password_confirmation"},
     *              @OA\Property(property="name", type="string", example="John Doe"),
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="password", type="string", example="Password123"),
     *              @OA\Property(property="password_confirmation", type="string", example="Password123")
     *          )
     *      ),
     *      @OA\Response(response=201, description="User registered successfully"),
     *      @OA\Response(response=422, description="Validation errors")
     * )
     */
    public function register(RegisterRequest $request)
    {
        $response = $this->userService->registerUser($request->validated());

        return $this->UserSuccess('User created successfully', new UserResource($response['user']), $response['token']);
    }

    /**
     * @OA\Post(
     *      path="/login",
     *      summary="Log in a user",
     *      description="Authenticate user with email and password",
     *      tags={"Authentication"},
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email", "password"},
     *              @OA\Property(property="email", type="string", example="john@example.com"),
     *              @OA\Property(property="password", type="string", example="Password123")
     *          )
     *      ),
     *      @OA\Response(response=200, description="User logged in successfully"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function login(LoginRequest $request)
    {
        try {
            $response = $this->userService->loginUser($request->validated());

            Log::info('User logged in successfully', ['user' => $response['user']]);
            return $this->UserSuccess('User Logged in successfully', new UserResource($response['user']), $response['token']);

        } catch (\Exception $e) {

            return $this->failure($e->getMessage());
        }
    }


    /**
     * @OA\Post(
     *      path="/logout",
     *      summary="Log out a user",
     *      description="Logout a user and delete their API token",
     *      tags={"Authentication"},
     *      security={{ "sanctum": {} }},
     *      @OA\Response(response=200, description="User logged out successfully"),
     *      @OA\Response(response=401, description="Unauthorized")
     * )
     */
    public function logout(Request $request)
    {
        try {
            $user = $request->user();

            if ($request->query('all') == 'true') {
                // Revoke all tokens
                $user->tokens()->delete();
                $message = 'Logged out from all devices successfully';
            } else {
                // Revoke only the current token
                $user->currentAccessToken()->delete();
                $message = 'Logged out successfully';
            }

            return $this->singleSuccessResponse($message);
        } catch (\Exception $e) {
            return $this->failure('An error occurred during logout', $e);
        }
    }


}
