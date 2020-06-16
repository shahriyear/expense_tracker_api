<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Support\Facades\Hash;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    const STATUS = [
        'ACTIVE' => 1,
        'DELETE' => 9,
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    public function getIdAttribute($value)
    {
        return idEncode($value);
    }
    public static function findId($id, $decode = true)
    {
        if ($decode) {
            $id = idDecode($id);
        }
        return User::where('id', $id)->where('status', self::STATUS['ACTIVE'])->first();
    }
    public function getCreatedByAttribute($value)
    {
        return idEncode($value);
    }

    public function getStatusAttribute($value)
    {
        return array_search($value, self::STATUS);
    }

    public static function addUser($data)
    {
        DB::beginTransaction();
        $user = new User;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = Hash::make($data->password);
        $user->created_by = idDecode($data->auth->id);

        if (!$user->save()) {
            DB::rollBack();
            return 'false';
        }

        DB::commit();
        return $user->id;
    }

    public static function updateUser($data)
    {
        DB::beginTransaction();
        $user = User::findId($data->id);
        $user->name = $data->name;
        $user->email = $data->email;

        if (!$user->save()) {
            DB::rollBack();
            return 'false';
        }

        DB::commit();
        return $user->id;
    }

    public static function checkParentId($parent_id)
    {
        if (!User::findId($parent_id))
            return false;
        return true;;
    }

    public static function getAll()
    {
        return User::where(['status' => self::STATUS['ACTIVE']])->get();
    }

    public static function getOne($id)
    {
        return User::findId($id);
    }

    public static function doRemove($id)
    {
        return self::changeStatus($id, 'DELETE');
    }

    private static function changeStatus($id, $status)
    {
        $upper_status = strtoupper($status);
        $user = User::findId($id);

        if (!$user && !array_key_exists($upper_status, self::STATUS)) {
            return false;
        }

        $user->status = self::STATUS[$status];

        if (!$user->save())
            return false;
        return true;
    }
}
