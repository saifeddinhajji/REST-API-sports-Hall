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

    protected $fillable = ['activity_id','name','duration','price','description','number_of_sessions'];

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
            'description' => 'required|string',
            'duration'=>'required|numeric',
            'price' => 'required|numeric',
            'activity_id' => 'required|exists:activities,id',

        ];
    protected $casts = [];

    public function  activity()
    {
        return $this->belongsTo(Activity::class,'activity_id');
    }

}
