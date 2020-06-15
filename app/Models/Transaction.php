<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Transaction extends Model
{
    const STATUS = [
        'ACTIVE' => 1,
    ];
    protected $table = 'transactions';
    public $timestamps = false;
    protected $fillable = [
        'category_id', 'amount', 'description', 'cause', 'explanation', 'created_by'
    ];

    protected $hidden = ['status'];

    public function getIdAttribute($value)
    {
        return idEncode($value);
    }

    public function getCategoryIdAttribute($value)
    {
        return idEncode($value);
    }

    public function getCreatedByAttribute($value)
    {
        return idEncode($value);
    }

    public function getStatusAttribute($value)
    {
        return array_search($value, self::STATUS);
    }

    public function getCreatedAtAttribute($value)
    {
        return date("Y-m-d H:i:s", strtotime($value));
    }

    public static function findId($id, $decode = true)
    {
        if ($decode) {
            $id = idDecode($id);
        }
        return Transaction::where('id', $id)->where('status', self::STATUS['ACTIVE'])->first();
    }

    private static function causeChecker($cause)
    {
        if ($cause === 'deposit') {
            return intval(1);
        } else if ($cause === 'expense') {
            return intval(-1);
        }
    }

    private static function explanation($data)
    {
        $category = Category::findId($data->category_id);
        return number_format($data->amount, 2) . " tk $data->cause for $category->name";
    }

    private static function amountSign($amount, $cause)
    {
        return floatval(number_format($amount, 2) * self::causeChecker($cause));
    }

    public static function addTransaction($data)
    {;
        if (!($amount = self::amountSign($data->amount, $data->cause))) {
            return 'false';
        }

        DB::beginTransaction();
        $tran = new Transaction;
        $tran->amount = $amount;
        $tran->description = $data->description;
        $tran->cause = $data->cause;
        $tran->explanation = self::explanation($data);
        $tran->category_id = idDecode($data->category_id);
        $tran->created_by = idDecode($data->auth->id);

        if (!$tran->save()) {
            DB::rollBack();
            return 'false';
        }

        DB::commit();
        return $tran->id;
    }

    public static function checkCategoryId($category_id)
    {
        if (!Category::findId($category_id))
            return false;
        return true;;
    }

    public static function getAll()
    {
        return Transaction::where(['status' => self::STATUS['ACTIVE']])->get();
    }

    public static function getOne($id)
    {
        return Transaction::findId($id);
    }
}