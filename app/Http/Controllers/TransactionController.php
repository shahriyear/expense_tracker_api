<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Validated;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id'     => 'required',
            'amount'  => 'required|numeric',
            'month'  => 'required|numeric',
            'year'  => 'required|numeric',
            'description'  => 'nullable',
        ]);

        if ($validator->fails()) {
            return response422($validator->errors());
        }

        if (!Transaction::checkCategoryId($request->category_id)) {
            return response404('category id could not found!');
        }

        if (!Transaction::monthChecker($request->month)) {
            return response422('month is not valid!');
        }

        if (!Transaction::yearChecker($request->year)) {
            return response422('year is not valid!');
        }

        if (($id = Transaction::addTransaction($request)) === 'false') {
            return response500('transaction can not inserted!');
        }

        return response201WithTypeAndMessage('transactions', ['id' => $id, 'message' => 'transaction added successfully!']);
    }


    public function all()
    {
        $transactions = Transaction::getAll();
        return response200WithType('transactions', $transactions);
    }

    public function one(Request $request)
    {
        if (!$request->id) {
            return response400('no id provided!');
        }
        if (!($transaction = Transaction::getOne($request->id))) {
            return response404('transaction not found!');
        }
        return response200WithType('transactions', $transaction);
    }




    public function report(Request $request)
    {
        if (isset($request->month) && !Transaction::monthChecker($request->month)) {
            return response422('month is not valid!');
        }

        if (isset($request->year) && !Transaction::yearChecker($request->year)) {
            return response422('year is not valid!');
        }

        if (!($tran = Transaction::report($request->month, $request->year))) {
            return response500('reports not found!');
        }
        return response200WithType('reports', $tran);
    }
}
