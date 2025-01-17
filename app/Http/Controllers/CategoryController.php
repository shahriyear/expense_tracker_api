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
            'name'     => 'required|unique:categories',
            'type'     => 'required|alpha',
            'parent_id'  => 'nullable'
        ]);

        if ($validator->fails()) {
            return response422($validator->errors());
        }

        if (isset($request->parent_id) && !Category::checkParentId($request->parent_id)) {
            return response404('parent id could not found!');
        }

        if (!Category::typeChecker($request->type)) {
            return response404('please provide a valid type!');
        }

        if (($id = Category::addCategory($request)) === 'false') {
            return response500('category can not inserted!');
        }

        return response201WithTypeAndMessage('categories', ['id' => $id, 'message' => 'category added successfully!']);
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

        if (!(Category::findId($request->id))) {
            return response404('category not found!');
        }

        if (isset($request->parent_id) && !Category::checkParentId($request->parent_id)) {
            return response404('parent id could not found!');
        }

        if (($id = Category::updateCategory($request)) === 'false') {
            return response500('category can not updated!');
        }

        return response200WithTypeAndMessage('categories', ['id' => $id, 'message' => 'category updated successfully!']);
    }



    public function all()
    {
        $categories = Category::getAll();
        return response200WithType('categories', $categories);
    }

    public function one(Request $request)
    {
        if (!$request->id) {
            return response204('no id provided!');
        }
        if (!($category = Category::getOne($request->id))) {
            return response404('category not found!');
        }
        return response200WithType('categories', $category);
    }

    public function del(Request $request)
    {
        if (!$request->id) {
            return response204('no id provided!');
        }

        if (!(Category::findId($request->id))) {
            return response404('category not found!');
        }

        if ((Category::checkCategoryInTransaction($request->id))) {
            return response404('category can not delete! its already used in transactions!');
        }

        if (!(Category::doRemove($request->id))) {
            return response500("category failed to delete!");
        }
        return response200WithTypeAndMessage('categories', ['message' => 'category deleted successfully!']);
    }
}
