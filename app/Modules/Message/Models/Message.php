<?php


namespace App\Modules\Message\Models;


use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';

    protected $fillable = [
        'user_id',
        'chat_id',
        'text',
    ];
}
