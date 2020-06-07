<?php


namespace App\Modules\Chat\Models;


use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';

    protected $fillable = [
        'owner_user_id',
        'is_conference',
    ];
}
