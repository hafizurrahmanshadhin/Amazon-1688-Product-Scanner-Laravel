<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Http\Resources\Api\Auth\RegisterResource;
use App\Services\Api\Auth\RegisterService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;

class RegisterController extends Controller {
    private RegisterService $registerService;
    private Helper $helper;

    public function __construct(RegisterService $registerService, Helper $helper) {
        $this->registerService = $registerService;
        $this->helper          = $helper;
    }

    /**
     * Handle user registration.
     */
    public function register(RegisterRequest $request): JsonResponse {
        try {
            $data   = $request->validated();
            $result = $this->registerService->register($data);

            return response()->json([
                'status'     => true,
                'message'    => 'User registered successfully.',
                'code'       => 201,
                'token_type' => 'bearer',
                'token'      => $result['token'],
                'data'       => new RegisterResource($result['user']),
            ], 201);
        } catch (JWTException $e) {
            Log::error('JWT Error: ' . $e->getMessage());
            return $this->helper->jsonResponse(false, 'JWT error occurred during registration.', 500, ['error' => $e->getMessage()]);
        } catch (ModelNotFoundException $e) {
            Log::error('Model not found: ' . $e->getMessage());
            return $this->helper->jsonResponse(false, 'User model not found.', 404, ['error' => $e->getMessage()]);
        } catch (Exception $e) {
            Log::error('Registration error: ' . $e->getMessage());
            return $this->helper->jsonResponse(false, 'An error occurred during registration.', 500, ['error' => $e->getMessage()]);
        }
    }
}
