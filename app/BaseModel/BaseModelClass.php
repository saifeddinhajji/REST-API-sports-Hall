<?php

namespace App\BaseModel;

use Illuminate\Database\Eloquent\Model;

class BaseModelClass extends Model
{
    use BaseModel;
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];
}
