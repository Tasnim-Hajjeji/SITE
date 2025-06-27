<?php

namespace App\Models;

use Faker\Guesser\Name;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable =[
        'name',
        'email',
        'subject',
        'message',
    ];
}
