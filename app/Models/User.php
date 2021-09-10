<?php

namespace App\Models;
use App\BaseModel\BaseModelUser;
use App\Libs\Result;
use Eloquent;
use Illuminate\Validation\Rule;
use Auth;
use Illuminate\Support\Facades\Auth as FacadesAuth;

/**
 * @mixin Eloquent
 */
class User extends BaseModelUser
{

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [ 'first_name', 'last_name', 'status','email', 'password', 'phone', 'photo', 'role', 'address','gym_id',"status" ];
    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];
    /**
     * The attributes that should be cast to native types.
     * @var array
     */

    public $roleDataCreate =
        [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|unique:users,phone',
            'role' => 'required|string',
            'password' => 'nullable|string',
            'address'=>'nullable|string',
            'gym_id' => 'nullable|exists:gyms,id'
        ];

    protected $with=['gym'];
    protected $casts = ["status"=>"boolean" ];

    public function eventBeforeCreateUpdate(array &$rawData, Result $res, $updateId = null): Result
    {
        if (isset($rawData['password']))
           {
            $rawData['password'] = bcrypt($rawData['password']);
           }
        return $res;
    }
    public function  gym()
    {
        return $this->belongsTo(Gym::class,'gym_id');
    }

    public function UpdateOne(array $data, $updateId): Result
    {
        $this->roleDataUpdate = [
            'first_name' => '',
            'last_name' => '',
            'email' => [Rule::unique('users', 'email')->ignore($updateId)],
            'phone' => [Rule::unique('users', 'phone')->ignore($updateId)],
            'role' => '',
            'password' => '',
            'status'=>''

        ];

        return $this->CreateOne($data, $updateId);
    }
}
