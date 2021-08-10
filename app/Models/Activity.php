<?php
namespace App\Models;
use App\BaseModel\BaseModelClass;
class Activity extends BaseModelClass
{
    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = ['name','coach_id', 'description','status'];

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
            'name' => 'required|unique:activities,name',
            'description' => 'required',
            'coach_id' => 'required|unique:activities,name',
            'status'=>'nullable|boolean'
        ];
    protected $with=['coach'];
    protected $casts = ['status'=>'boolean'];

    public  function  coach()
    {
        return $this->belongsTo(User::class,'coach_id');
    }
}

