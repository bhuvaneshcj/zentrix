<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
|--------------------------------------------------------------------------
| Relationships
|--------------------------------------------------------------------------
*/

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function categories()
    {
        return $this->hasMany(NoteCategory::class);
    }

    public function tags()
    {
        return $this->hasMany(NoteTag::class);
    }
}
