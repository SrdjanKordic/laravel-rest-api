<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserActivity extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type', // default,error,info
        'operation', // update, create, delete
        'description', // log description
        'subject_type',
        'subject_id',
        'user_id',
        'user_ip',
        'user_agent',
        'user_os',
        'user_os_version',
        'user_platform',
        'properties',
        'created_at',
        'updated_at'
    ];
}
