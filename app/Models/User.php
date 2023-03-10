<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $fillable = ['email'];

    public function rubrics()
    {
        return $this->belongsToMany(Rubric::class, 'subscriptions');
    }
}