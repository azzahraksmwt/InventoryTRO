<?php

namespace App\Models;

//use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $primaryKey = 'idPengguna';
    protected $keyType = 'string';
    protected $fillable = [
        'idPengguna',
        'namaPengguna',
        'kelas',
        'nohp',
        'foto',
        'angkatan',
        'username',
        'password',
        'admin',
        'role',
    ];

    public function update(array $attributes = [], array $options = [])
    {
        return parent::update($attributes, $options);
    }

    public function inventorys()
    {
        return $this->hasMany(Inventory::class, 'idPengguna', 'idPengguna');
    }

    public function user()
    {
        return $this->hasMany(User::class, 'idPengguna', 'idPengguna');
    }

    public static function getAdmin()
    {
        return self::where('role', 'Admin')->value('namaPengguna');
    }

    public function getAuthPassword()
    {
         return $this->password;
    }

    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'username',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
