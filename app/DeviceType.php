<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    public $fillable = ['name'];

    public function devices()
    {
        return $this->hasMany('App\Device', 'type_id');
    }

    public function values()
    {
        return $this->hasMany('App\DeviceTypeValues', 'type_id');
    }
}
