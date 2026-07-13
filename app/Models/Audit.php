<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    protected $fillable = [
        'nama_audit',
        'periode_id',
        'instrumen_path',
        'final_ptpp_pdf',
        'tanggal_audit',
        'unit_id',
        'wakil_auditi_id',
        'dibuat_oleh',
        'auditor_1_id',
        'auditor_2_id',
        'lead_auditor_id',
    ];

    // Relasi
    public function periode()
    {
        return $this->belongsTo(Periode::class);
    }

    public function temuan()
    {
        return $this->hasMany(TemuanAudit::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function wakilAuditi()
    {
        return $this->belongsTo(User::class, 'wakil_auditi_id');
    }

    public function auditor1()
    {
        return $this->belongsTo(User::class, 'auditor_1_id');
    }

    public function auditor2()
    {
        return $this->belongsTo(User::class, 'auditor_2_id');
    }

    public function leadAuditor()
    {
        return $this->belongsTo(
            User::class,
            'lead_auditor_id'
        );
    }

    // Helper
    public function isAuditor1($userId)
    {
        return $this->auditor_1_id == $userId;
    }

    public function isAuditor2($userId)
    {
        return $this->auditor_2_id == $userId;
    }
}
