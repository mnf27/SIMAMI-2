<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Audit;
use App\Models\User;

class TemuanAudit extends Model
{
    protected $fillable = [
        'audit_id',
        'kode_indikator',
        'temuan',
        'hasil_ami',
        'tindakan_perbaikan_awal',
        'bukti_link',
        'tanggapan_auditor',
        'tanggapan_auditor_2',
        'status',
        'needs_review',
        'review_finalized',
    ];

    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }

    public function users()
    {
        return $this->belongsToMany(
            User::class,
            'temuan_users',
            'temuan_id',
            'user_id'
        );
    }
}
