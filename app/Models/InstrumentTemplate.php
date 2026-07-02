<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstrumentTemplate extends Model
{
    use HasFactory;
    protected $fillable = [
        'nama_file',
        'path',
        'versi',
        'is_active',
        'uploaded_by',
    ];
    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }
}
