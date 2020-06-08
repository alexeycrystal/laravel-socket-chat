<?php


namespace App\Modules\Chat\Models;


use Illuminate\Database\Eloquent\Model;

class ChatUser extends Model
{
    protected $table = 'chat_user';

    protected $fillable = [
        'chat_id',
        'user_id',
        'is_visible',
    ];

    public $timestamps = null;
}
