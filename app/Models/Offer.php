<?php

namespace App\Models;

use App\BaseModel\BaseModelClass;

class Offer extends BaseModelClass
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name','duration','price','unit','status'];
    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = ['updated_at'];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    public $roleDataCreate =
        [
            'name' => 'required|string',
            'price' => 'required||numeric',
            'duration'=>'required|numeric',
            'status'=>'nullable|boolean',
            'unit'=>'required|string|in:day,month,year'
        ];

    public $roleDataUpdate =
        [
            'status'=>'nullable|boolean'
        ];
    protected $casts = [
        'status'=>'boolean'
    ];

}
