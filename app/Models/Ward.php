<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    protected $fillable = ['name'];

    public function areaCodes()
    {
        return $this->hasMany(AreaCode::class);
    }
}
