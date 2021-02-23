<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Webuser extends Model
{
    protected $dates =['paswword_at','login_at'];

    protected $fillable = [
        'id', 'password', 'password_at', 'facultativo_id', 'maxses', 'maxdias', 'intentos', 'blocked', 'traza', 'bono', 'condiciones', 'login_at','ip','username'
    ];
}
