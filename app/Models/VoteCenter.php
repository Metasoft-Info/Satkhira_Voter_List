<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VoteCenter extends Model
{
    protected $fillable = ['center_no', 'name_bn', 'name_en'];

    public function areaCodes()
    {
        return $this->hasMany(AreaCode::class);
    }
}
