<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;


class Category extends Model
{
    const STATUS = [
        'ACTIVE' => 1,
        'DELETE' => 9,
    ];
    protected $table = 'categories';
    protected $fillable = [
        'name', 'parent_id', 'status', 'created_by'
    ];
    protected $hidden = [];

    public function getIdAttribute($value)
    {
        return idEncode($value);
    }

    public function getParentIdAttribute($value)
    {
        return idEncode($value);
    }

    public static function findId($id, $decode = true)
    {
        if ($decode) {
            $id = idDecode($id);
        }
        return Category::where('id', $id)->where('status', self::STATUS['ACTIVE'])->first();
    }

    public static function addCategory($data)
    {
        DB::beginTransaction();
        $category = new Category;
        $category->name = $data->name;
        $category->created_by = idDecode($data->auth->id);
        $category->parent_id = $data->parent_id ? idDecode($data->parent_id) : 0;

        if (!$category->save()) {
            DB::rollBack();
            return 'false';
        }

        DB::commit();
        return $category->id;
    }

    public static function updateCategory($data)
    {
        DB::beginTransaction();
        $category = Category::findId($data->id);
        $category->name = $data->name;
        $category->created_by = idDecode($data->auth->id);
        $category->parent_id = $data->parent_id ? idDecode($data->parent_id) : 0;

        if (!$category->save()) {
            DB::rollBack();
            return 'false';
        }

        DB::commit();
        return $category->id;
    }

    public static function checkParentId($parent_id)
    {
        if (!Category::findId($parent_id))
            return false;
        return true;;
    }

    public static function getAll()
    {
        return Category::where(['status' => self::STATUS['ACTIVE']])->get();
    }

    public static function getOne($id)
    {
        return Category::findId($id);
    }

    public static function doRemove($id)
    {
        return self::changeStatus($id, 'DELETE');
    }

    private static function changeStatus($id, $status)
    {
        $upper_status = strtoupper($status);
        $category = Category::findId($id);

        if (!$category && !array_key_exists($upper_status, self::STATUS)) {
            return false;
        }

        $category->status = self::STATUS[$status];

        if (!$category->save())
            return false;
        return true;
    }
}
