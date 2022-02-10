<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access_User extends Model
{
    use HasFactory;
    protected $fillable=[
        'user_id',
        'access_id'];
}
