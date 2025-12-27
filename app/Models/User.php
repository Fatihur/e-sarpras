<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'password',
        'role',
        'aktif',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
            'aktif' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isManajemen(): bool
    {
        return $this->role === 'manajemen';
    }

    public function isPimpinan(): bool
    {
        return $this->role === 'pimpinan';
    }
        public function isAdminOrManajemen()
    {
        return in_array($this->role, ['admin', 'manajemen']);
    }
}
