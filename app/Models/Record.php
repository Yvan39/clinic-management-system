<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Record extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'medical_history',
        'allergies',
        'smoker',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }
    // Mutators to encrypt data when saving
    public function setMedicalHistoryAttribute($value)
    {
        $this->attributes['medical_history'] = $value ? Crypt::encryptString($value) : null;
    }
    public function setAllergiesAttribute($value)
    {
        $this->attributes['allergies'] = $value ? Crypt::encryptString($value) : null;
    }
    // Accessors to decrypt data when retrieving
    public function getMedicalHistoryAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null; // Or handle the error as needed
        }
    }
    public function getAllergiesAttribute($value)
    {
        try {
            return $value ? Crypt::decryptString($value) : null;
        } catch (\Exception $e) {
            \Log::error('Decryption error: ' . $e->getMessage());
            return null; // Or handle the error as needed
        }
    }
 
}
