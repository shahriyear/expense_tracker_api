<?php

namespace App\Http\Controllers;

use App\User;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'     => 'required|email|unique:users',
            'password'     => 'required'
        ]);

        if ($validator->fails()) {
            return response422($validator->errors());
        }

        if (($id = User::addUser($request)) === 'false') {
            return response500('user can not inserted!');
        }

        return response201WithTypeAndMessage('users', ['id' => $id, 'message' => 'user added successfully!']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'email'     => 'required|email|unique:users',
        ]);

        if ($validator->fails()) {
            return response422($validator->errors());
        }

        if (!(User::findId($request->id))) {
            return response404('user not found!');
        }

        if (($id = User::updateUser($request)) === 'false') {
            return response500('user failed to update!');
        }

        return response200WithTypeAndMessage('users', ['id' => $id, 'message' => 'user updated successfully!']);
    }



    public function all()
    {
        $users = User::getAll();
        return response200WithType('users', $users);
    }

    public function one(Request $request)
    {
        if (!$request->id) {
            return response400('no id provided!');
        }
        if (!($user = User::getOne($request->id))) {
            return response404('user not found!');
        }
        return response200WithType('users', $user);
    }

    public function del(Request $request)
    {
        if (!$request->id) {
            return response400('no id provided!');
        }

        if (!(User::findId($request->id))) {
            return response404('user not found!');
        }

        if (!(User::doRemove($request->id))) {
            return response500('user failed to delete!');
        }
        return response200WithTypeAndMessage('users', ['message' => 'user deleted successfully!']);
    }
}
