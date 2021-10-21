<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'type',             // default,error,info , for now its default
        'operation',        // update, create, delete
        'description',      // Log description
        'subject_type',     // Subject Type - On what model is option 
        'subject_id',
        'causer_type',
        'causer_id',
        'causer_ip',
        'causer_agent',
        'causer_os',
        'causer_os_version',
        'causer_platform',
        'properties',
        'created_at',
        'updated_at'
    ];
}
