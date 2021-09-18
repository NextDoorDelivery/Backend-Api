<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LkVerificationStatus extends Model
{
    use HasFactory;


    protected $fillable = [
        'LkVerificationStatusId',
        'description',
    ];
}
