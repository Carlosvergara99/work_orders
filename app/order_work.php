<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class order_work extends Model
{
    protected $table = 'order_work';
    protected $guarded = ['id'];

    public function ordertype()
    {
     return $this->belongsTo('App\types_work', 'types_work_id');
    }

    public function status()
    {
     return $this->belongsTo('App\status', 'status_id');
    }

    public function order ()
    {
        return $this->hasMany('App\order');

    }

    public function getNumberCodeAttribute()
    {
        return "BS-{$this->order_number}";

    }
}
