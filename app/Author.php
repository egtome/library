<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Author extends Model
{
    //protected $fillable = ['name','dob'];
    protected $dates = ['dob'];
    protected $guarded = [];
    public function setaDobAttribute($dob)
    {
        $this->attributes['dob'] = Carbon::parse($dob);
    }
}
