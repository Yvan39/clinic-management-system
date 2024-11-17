<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'gender',
        'birthday',
        'type',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
    ];

    public function records()
    {
        return $this->hasOne(Record::class, 'patient_id');
    }
    public function treatments()
    {
        return $this->hasMany(Treatment::class, 'patient_id');
    }
    public function xrays()
    {
        return $this->hasMany(Xray::class, 'patient_id');
    }
    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'patient_id');
    }
    // Mutators to encrypt data when saving
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value ? Crypt::encryptString($value) : null;
    }
    public function setAddressAttribute($value)
    {
        $this->attributes['address'] = $value ? Crypt::encryptString($value) : null;
    }
    // Accessors to decrypt data when retrieving
    public function getPhoneAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null;
        }
    }
    public function getAddressAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null;
        }
    }
}