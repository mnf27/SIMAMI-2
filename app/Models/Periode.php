<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Helpers\SemesterHelper;

class Periode extends Model
{
    protected $fillable = [
        'kode'
    ];

    public function getNamaAttribute()
    {
        return $this->kode;
    }

    public function getLabelAttribute()
    {
        return SemesterHelper::label($this->kode);
    }

    public function getDisplayAttribute()
    {
        return $this->kode.' '.$this->label;
    }

    public function audits()
    {
        return $this->hasMany(
            Audit::class,
            'periode_id',
            'id'
        );
    }
}
