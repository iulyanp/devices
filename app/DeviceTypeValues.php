<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceTypeValues extends Model
{
    public $fillable = ['value', 'label', 'type_id'];
    public $timestamps = false;

    public function type()
    {
        return $this->belongsTo('App\DeviceType', 'type_id');
    }
}
