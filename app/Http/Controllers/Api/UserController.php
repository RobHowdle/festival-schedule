<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User; // Import the User model
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse; // For explicit return type
use Illuminate\Validation\Rule; // Required for unique email validation in update method
use Illuminate\Support\Facades\Hash; // Required for password hashing in store/update methods
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Display a listing of the users (for API).
     * Corresponds to GET /admin/users/api
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $query->orderBy('name');
        $users = $query->get(['id', 'name', 'email', 'is_admin', 'is_dev']);

        return response()->json($users);
    }

    /**
     * Store a newly created user in storage (for API).
     * Corresponds to POST /admin/users
     * You would use this for an "Add New User" form.
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' means password_confirmation field is required
            'is_admin' => ['boolean'], // Boolean field, not required for creation
            'is_dev' => ['boolean'], // Boolean field, not required for creation
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'is_admin' => $validatedData['is_admin'] ?? false, // Default to false if not provided
            'is_dev' => $validatedData['is_dev'] ?? false, // Default to false if not provided
        ]);

        return response()->json(['message' => 'User created successfully!', 'user' => $user], 201); // 201 Created
    }

    /**
     * Update the specified user in storage (for API).
     * Corresponds to PATCH /admin/users/{user}
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'is_admin' => ['required', 'boolean'],
            'is_dev' => ['required', 'boolean'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'], // This means it's optional
        ]);

        // Update name, email, is_admin
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->is_admin = $validatedData['is_admin'];
        $user->is_dev = $validatedData['is_dev'];

        // --- CRUCIAL CHANGE FOR PASSWORD ---
        // Only update the password if a new one was actually provided in the request
        // (i.e., it passed the 'nullable' validation but was not empty)
        if ($request->filled('password')) { // <-- Use $request->filled() for robustness
            $user->password = Hash::make($validatedData['password']);
        }
        // --- END CRUCIAL CHANGE ---

        $user->save();

        return Redirect::route('admin.dashboard', [], 303)->with('message', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage (for API).
     * Corresponds to DELETE /admin/users/{user}
     */
    public function destroy(User $user): JsonResponse
    {
        $user->delete();

        return response()->json(['message' => 'User deleted successfully!'], 204); // 204 No Content
    }
}