<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Upazila extends Model
{
    protected $fillable = ['name'];

    public function unions()
    {
        return $this->hasMany(Union::class);
    }

    public function areaCodes()
    {
        return $this->hasMany(AreaCode::class);
    }
}
