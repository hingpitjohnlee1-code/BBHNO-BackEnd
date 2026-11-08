<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // GET /api/users
    public function index()
    {
        return UserResource::collection(User::all());
    }

    // POST /api/users
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'contact_no' => 'nullable|string|max:20',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact_no' => $request->contact_no,
        ]);

        return new UserResource($user);
    }

    // GET /api/users/{id}
    public function show(User $user)
    {
        return new UserResource($user);
    }

    // PUT/PATCH /api/users/{id}
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'contact_no' => 'nullable|string|max:20',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'contact_no' => $request->contact_no,
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:8',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return new UserResource($user);
    }

    // DELETE /api/users/{id} - Admin only
    public function destroy(Request $request, User $user)
    {
        // Check if the logged-in user is an Admin
        if ($request->user()->role !== 'admin') {
            return response()->json([
                'message' => 'Forbidden: Only admins can delete users'
            ], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User deleted successfully'
        ], 200);
    }
}