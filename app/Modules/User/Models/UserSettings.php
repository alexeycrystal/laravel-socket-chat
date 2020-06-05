<?php


namespace App\Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class UserSettings extends Model
{
    protected $table = 'user_settings';

    protected $fillable = [
        'user_id',

        'nickname',

        'timezone',

        'phone',

        'latitude',
        'longitude',

        'city',
        'region',
        'country',

        'lang',

        'avatar_path',
    ];
}
