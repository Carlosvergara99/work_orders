<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    protected $table = 'order';
    protected $guarded = ['id'];

    public function orderwork()
    {
     return $this->belongsTo('App\order_work', 'order_work_id');
    }

    public function operator()
    {
     return $this->belongsTo('App\User', 'users_id');
    }
}
