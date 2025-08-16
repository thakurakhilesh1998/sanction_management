<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordHistories extends Model
{
    use HasFactory;
    protected $table='password_histories';
    protected $fillable = ['user_id','password'];
}
