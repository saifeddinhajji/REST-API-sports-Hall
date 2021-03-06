<?php

namespace App\Models;
use App\BaseModel\BaseModelClass;

class TypeSubscription extends BaseModelClass
{
    protected $table="types_subscriptions";
    /**
     * The attributes that are mass assignable.
     * @var array
     */

    protected $fillable = ['activity_id','name','duration','price','description','unit','number_of_sessions','status'];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = ['updated_at'];
protected $with=['activity'];
    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    public $roleDataCreate =
        [
            'name' => 'required|string',
            'description' => 'nullable|string',
            'duration'=>'required|numeric',
            'price' => 'required|numeric',
            'unit'=>'required|in:day,month,year',
            'activity_id' => 'required|exists:activities,id'

        ];
    protected $casts = [
        'status'=>'boolean'
    ];

    public function  activity()
    {
        return $this->belongsTo(Activity::class,'activity_id');
    }

}
