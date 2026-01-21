<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AreaCode extends Model
{
    protected $fillable = ['upazila_id', 'union_id', 'ward_id', 'vote_center_id', 'area_code_no'];

    public function upazila()
    {
        return $this->belongsTo(Upazila::class);
    }

    public function union()
    {
        return $this->belongsTo(Union::class);
    }

    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    public function voteCenter()
    {
        return $this->belongsTo(VoteCenter::class);
    }
}
