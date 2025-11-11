<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::with(['users:id,name,email,is_active'])
            ->orderBy('name')
            ->get();

        return response()->json($roles);
    }

    public function show($id)
    {
        $role = Role::with(['users:id,name,email,is_active'])->findOrFail($id);

        return response()->json($role);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required','string','max:100', Rule::unique('roles','name')],
        ]);

        $role = Role::create($data);

        return response()->json($role, 201);
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name' => ['required','string','max:100', Rule::unique('roles','name')->ignore($role->id)],
        ]);

        $role->update($data);

        return response()->json($role);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(['message' => 'Role deleted successfully'], 200);
    }
}