<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Answer;

class Question extends Model
{
    public function answers()
    {
        return $this->hasMany('App\Answer');
    }
}
