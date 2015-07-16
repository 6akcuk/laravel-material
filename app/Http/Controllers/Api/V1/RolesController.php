<?php

namespace App\Http\Controllers\Api\V1;

use App\Authority\Role;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
    public function index(Request $request)
    {
        $asc = preg_match("/^[\-]{1}/", $request->input('order', '-created_at')) ? 'DESC' : 'ASC';
        $order = preg_replace("/^[\-]{1}/", "", $request->input('order', '-created_at'));

        $stmt = Role::orderBy($order, $asc);
        if ($request->input('filter')) {
            $q = $request->input('filter');

            $stmt->where('name', 'like', '%' . $q . '%');
        }

        $roles = $stmt->paginate($request->input('limit'));

        return response()->json($roles);
    }

    public function store(RoleRequest $request)
    {
        Role::create([
            'name' => $request->name
        ]);
    }

    public function show($id)
    {
        return response()->json(Role::findOrFail($id));
    }

    public function update(RoleRequest $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        return;
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();

        return;
    }
}
