<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkMobileDeviceToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'PhoneNumber',
        'DeviceUuid',
        'MobileAuthToken',
    ];

    // Must exist for tables that do not have time stamp columns.
    public $timestamps = false;

}
