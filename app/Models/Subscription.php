<?php
namespace App\Models;
use App\BaseModel\BaseModelClass;
use DateTime;
class Subscription extends BaseModelClass
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['type_subscriptions_id', 'adherent_id','start_at','end_at','status'];
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
            'adherent_id' => 'required|exists:users,id',
            'type_subscriptions_id' => 'required||exists:types_subscriptions,id',
            'start_at'=>'required|date|after_or_equal:today',
            'end_at' => 'required|date|after_or_equal:start_at',
        ];
    protected $casts = [];
    protected $appends=["is_terminated"];
    protected $with=['typesSubscription','adherent'];
    public function GetIsTerminatedAttribute()
    {
        $date=(new DateTime())->format('Y-m-d');
     if($this->getAttribute('end_at')<$date)
     {
         return "terminé";
     }
     elseif($this->getAttribute('end_at')>=$date && $this->getAttribute('start_at')<$date)
     {
         return "en cours";
     }
     else
     {
         return "pas en cours";
     }

    }
    public function  adherent()
    {
        return $this->belongsTo(\App\Models\User::class);
    }
    public function  typesSubscription()
    {
        return $this->belongsTo(TypeSubscription::class,'type_subscriptions_id');
    }
}
