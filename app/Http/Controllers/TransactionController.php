<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'     => 'required',
            'amount'  => 'required',
            'description'  => 'nullable',
            'cause'  => 'required',
        ]);

        if ($validator->fails()) {
            return response422($validator->errors());
        }

        if (isset($request->category_id) && !Transaction::checkCategoryId($request->category_id)) {
            return response404('category id could not found!');
        }

        if (($id = Transaction::addTransaction($request)) === 'false') {
            return response500('transaction can not inserted!');
        }

        return response201(['id' => $id, 'message' => 'transaction added successfully!']);
    }


    public function all()
    {
        $transactions = Transaction::getAll();
        return response200($transactions);
    }

    public function one(Request $request)
    {
        if (!$request->id) {
            return response400('no id provided!');
        }
        if(!($transaction = Transaction::getOne($request->id))){
            return response404('transaction not found!');
        }
        return response200($transaction);
    }
}
