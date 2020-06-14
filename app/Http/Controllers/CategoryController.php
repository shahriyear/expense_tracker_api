<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'parent_id'  => 'nullable'
        ]);

        if ($validator->fails()) {
            return response422($validator->errors());
        }


        if (isset($request->parent_id) && !Category::checkParentId($request->parent_id)) {
            return response400('parent id could not found!');
        }

        if (($id = Category::addCategory($request)) === 'false') {
            return response400('category can not inserted!');
        }

        return response200(['id' => $id, 'message' => 'category added successfully!']);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required',
            'parent_id'  => 'nullable'
        ]);


        if ($validator->fails()) {
            return response422($validator->errors());
        }

        if (isset($request->parent_id) && !Category::checkParentId($request->parent_id)) {
            return response400('parent id could not found!');
        }

        if (($id = Category::updateCategory($request)) === 'false') {
            return response400('category can not updated!');
        }

        return response200(['id' => $id, 'message' => 'category updated successfully!']);
    }



    public function all()
    {
        $categories = Category::getAll();
        return response200($categories);
    }

    public function one(Request $request)
    {
        if (!$request->id) {
            return response400('no id provided!');
        }
        $category = Category::getOne($request->id);
        return response200($category);
    }

    public function del(Request $request)
    {
        if (!$request->id) {
            return response400('no id provided!');
        }

        $category = Category::doRemove($request->id);
        return response200($category);
    }
}
