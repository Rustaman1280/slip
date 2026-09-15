<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    // tabel user di database
    protected $table = 'users';

    // matiin timestamps bawaan laravel biar gak nyari updated_at
    public $timestamps = false;

    // kolom-kolom yang boleh diisi pas create atau update
    protected $fillable = [
        'username',
        'password',
        'nama_lengkap',
        'email',
        'role',
    ];

    // sembunyiin password biar aman pas data user di-output
    protected $hidden = [
        'password',
    ];

    // hash password otomatis biar aman
    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }
}
