<?php

namespace App\Models;

use App\BaseModel\BaseModelClass;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class SubscriptionGym extends BaseModelClass
{
    protected $table="subscription_gyms";
    /**
     * The attributes that are mass assignable.
     * @var array
     */

    protected $fillable = ['start_at','end_at','offer_id','gym_id','payment_receipt','status'];
    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = ['updated_at'];
    protected $with=['offer','gym'];
    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    public $roleDataCreate =
        [
            'gym_id' => 'required|exists:gyms,id',
            'offer_id' => 'required|exists:offers,id',
            'start_at'=>'required|date|after_or_equal:today',
            'end_at' => 'required|date|after_or_equal:start_at',
            'payment_receipt'=>'required|string'
        ];
    protected $casts = [];

    public function  offer()
    {
        return $this->belongsTo(Offer::class,'offer_id');
    }
    public function  gym()
    {
        return $this->belongsTo(Gym::class,'gym_id');
    }
}
