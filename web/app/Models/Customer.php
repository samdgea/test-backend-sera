<?php

namespace App\Models;

use Jenssegers\Mongodb\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $collection = 'customers';

    protected $fillable = [
        'first_name',
        'last_name',
        'email_address',
        'phone_number'
    ];

    protected $dates = ['deleted_at'];

}
