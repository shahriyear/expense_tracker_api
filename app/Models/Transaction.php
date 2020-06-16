<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Static_;

class Transaction extends Model
{
    const STATUS = [
        'ACTIVE' => 1,
    ];

    protected $table = 'transactions';
    public $timestamps = false;
    protected $fillable = [
        'category_id', 'month', 'year', 'amount', 'description', 'cause', 'explanation', 'created_by'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
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

    public static function findId($id, $decode = true)
    {
        if ($decode) {
            $id = idDecode($id);
        }
        return Transaction::where('id', $id)->where('status', self::STATUS['ACTIVE'])->first();
    }

    public static function monthChecker($month)
    {
        $m = intval($month);
        if ($m >= 1 && $m <= 12)
            return true;
        return false;
    }

    public static function yearChecker($year)
    {
        $y = intval($year);
        if ($y >= 2020 && $y <= 2099)
            return true;
        return false;
    }


    private static function explanation($data)
    {
        $category = Category::findId($data->category_id);
        return number_format($data->amount, 2) . " tk $data->cause for $category->name";
    }

    private static function amountSign($amount, $cause)
    {
        $val = Category::causeChecker($cause);
        if ($val === 0) {
            return 0;
        }
        return floatval(number_format($amount, 2) * Category::causeChecker($cause));
    }

    public static function addTransaction($data)
    {;
        $cause = Category::getCategoryTypeById($data->category_id);
        if ($cause == 'false') {
            return 'false';
        }

        $amount = self::amountSign($data->amount, $cause);
        if ($amount === 0) {
            return 'false';
        }

        DB::beginTransaction();
        $tran = new Transaction;
        $tran->amount = $amount;
        $tran->description = $data->description;
        $tran->cause = $cause;
        $tran->month = $data->month;
        $tran->year = $data->year;
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
