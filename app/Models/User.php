<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getInitial(){
        $name = $this->name;
        $words = explode(' ', trim($name));
        $firstInitial = mb_substr($words[0], 0, 1, 'UTF-8');
        $lastInitial = count($words) > 1 ? mb_substr(end($words), 0, 1, 'UTF-8') : '';
        return mb_strtoupper($firstInitial . $lastInitial, 'UTF-8');
    }

    public function pockets(){
        return $this->hasMany(Pocket::class);
    }
}
