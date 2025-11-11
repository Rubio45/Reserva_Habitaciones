<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles:id,name'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return response()->json($users);
    }

    public function show($id)
    {
        $user = User::with(['roles:id,name'])->findOrFail($id);

        return response()->json($user);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'           => ['required','string','max:120'],
            'email'          => ['required','email','max:120', Rule::unique('users','email')],
            'password'       => ['required','string','min:6'],
            'role_ids'       => ['nullable','array'],
            'role_ids.*'     => ['exists:roles,id'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        if (isset($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }

        $user->load('roles:id,name');

        return response()->json($user, 201);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'           => ['required','string','max:120'],
            'email'          => ['required','email','max:120', Rule::unique('users','email')->ignore($user->id)],
            'password'       => ['nullable','string','min:6'],
            'role_ids'       => ['nullable','array'],
            'role_ids.*'     => ['exists:roles,id'],
        ]);

        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = Hash::make($data['password']);
        }

        $user->update($updateData);

        if (isset($data['role_ids'])) {
            $user->roles()->sync($data['role_ids']);
        }

        $user->load('roles:id,name');

        return response()->json($user);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}