<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:user,admin'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Ensure password is hashed
                'role' => $request->role,
            ]);

            return response()->json(['message' => 'Registration successful!'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Something went wrong. Please try again.'], 500);
        }
    }

    /**
     * Login a user.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Fetch user from database
        $user = User::where('email', $credentials['email'])->first();

        // Debugging: Check if user exists
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Debugging: Check if password matches
        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json(['error' => 'Incorrect password.'], 401);
        }

        // Debugging: Check role
        if (!in_array($user->role, ['admin', 'user'])) {
            return response()->json(['error' => 'Invalid user role.'], 403);
        }

        // Revoke old tokens
        $user->tokens()->delete();

        // Generate new token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login successful!',
            'token' => $token,
            'role' => $user->role
        ]);
    }

    /**
     * Logout the user.
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(['message' => 'Logged out successfully.']);
    }

    /**
     * Get authenticated user details.
     */
    public function user(Request $request)
    {
        return response()->json(['user' => $request->user()]);
    }
}
