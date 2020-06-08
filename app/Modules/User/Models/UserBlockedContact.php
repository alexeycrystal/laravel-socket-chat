<?php


namespace App\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class UserBlockedContact extends Model
{
    protected $table = 'user_blocked_contacts';

    protected $fillable = [
        'user_id',
        'blocked_user_id',
    ];
}
