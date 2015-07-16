<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $asc = preg_match("/^[\-]{1}/", $request->input('order', '-created_at')) ? 'DESC' : 'ASC';
        $order = preg_replace("/^[\-]{1}/", "", $request->input('order', '-created_at'));

        $stmt = User::with('roles')->orderBy($order, $asc);
        if ($request->input('filter')) {
            $q = $request->input('filter');

            $stmt->where('name', 'like', '%' . $q . '%')
                ->orWhere('email', 'like', '%' . $q . '%');
        }

        $users = $stmt->paginate($request->input('limit'));

        return response()->json($users);
    }

    public function store(AddUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'is_active' => $request->is_active
        ]);
    }

    public function show($id)
    {
        return response()->json(User::findOrFail($id));
    }

    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->is_active = $request->is_active;
        $user->save();

        return;
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return;
    }
}