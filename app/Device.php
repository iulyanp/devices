<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    public $fillable = ['name', 'type_id', 'value', 'unit'];

    public function type()
    {
        return $this->belongsTo('App\DeviceType');
    }
}
