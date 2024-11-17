<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Xray extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'date',
        'type',
        'radiologist_report',
        'findings',
        'diagnosis',
        'follow_up',
        'image',
    ];

    protected $casts = ['date' => 'date'];

    public function patient () {
        return $this->belongsTo(User::class, 'patient_id');
    }
    // Mutators to encrypt data when saving
    public function setFindingsAttribute($value)
    {
        $this->attributes['findings'] = $value ? Crypt::encryptString($value) : null;
    }
    // Accessors to decrypt data when retrieving
    public function getFindingsAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null;
        }
    }
}