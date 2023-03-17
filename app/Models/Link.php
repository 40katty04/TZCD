<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class Link extends Model
{
    use HasFactory;

    protected $fillable = [
        'token',
        'max_clicks',
        'clicks',
        'expires_at',
    ];

    protected $casts = [
        'token' => 'string',
        'max_clicks' => 'integer',
        'clicks' => 'integer',
        'expires_at' => 'datetime',
    ];


    public static $rules = [
        'token' => [
            'required',
            'string',
            'size:8',                  // must be at 8 characters in length
            'regex:/[a-z]/',           // must contain at least one lowercase letter
            'regex:/[A-Z]/',           // must contain at least one uppercase letter
            'regex:/[0-9]/',         // must contain at least one digit
            'unique:links,token'
        ],
        'max_clicks' => ['integer', 'between:0,999999'],
        'expires_at' => ['date'],
       ];

    public function isExpired(): bool
    {
        return $this->expires_at !== null && Carbon::now()->greaterThan(Carbon::make($this->expires_at));
    }

    public function hasMaxClicks(): bool
    {
        return $this->max_clicks > 0 && $this->clicks >= $this->max_clicks;
    }

    public function incrementClicks(): void
    {
        $this->clicks++;
        $this->save();
    }

    public static function generateToken(){
        $token = Str::random(8);

     /*   if (self::query()->where(['token' => $token])->count() > 0){

            $token = self::generateToken();
        }*/

        return $token;
    }
}
