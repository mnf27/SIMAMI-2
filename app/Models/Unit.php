<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    protected $fillable = [
        'nama',
        'jenis',
        'lokasi',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function audits()
    {
        return $this->hasMany(Audit::class);
    }
}
