<?php
namespace App\Models;
use App\BaseModel\BaseModelClass;
class Gym extends BaseModelClass
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */

    protected $fillable = ['name','description','logo','code_fiscal','vacation_day','url_fcb'];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = ['created_at'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    public $roleDataCreate =
        [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'code_fiscal'=>'required|string',
            'logo'=>'required|string',
            'vacation_day'=>'required|string',
            'url_fcb'=>'required|string'
        ];
    protected $casts = [];

}
