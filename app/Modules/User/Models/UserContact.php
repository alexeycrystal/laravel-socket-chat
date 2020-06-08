<?php


namespace App\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class UserContact extends Model
{
    protected $table = 'user_contacts';

    protected $fillable = [
        'user_id',
        'contact_user_id',
        'alias',
    ];
}
