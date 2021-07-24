<?php
namespace App\Models;
use App\BaseModel\BaseModelClass;
class Subscription extends BaseModelClass
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */

    protected $fillable = ['type_subscriptions_id', 'adherent_id','start_at','end_at'];

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
            'activity_id' => 'required|exists:activities,id',
            'type_subscriptions_id' => 'required||exists:users,id',
            'start_at'=>'required|date|after_or_equal:today',
            'end_at' => 'required|date|after_or_equal:start_at',
        ];
    protected $casts = [];
protected $with=['activity','adherent'];
    public function  activity()
    {
        return $this->belongsTo(Activity::class,'activity_id');
    }
    public function  adherent()
    {
        return $this->belongsTo(\App\Models\User::class,'adherent_id');
    }
}
