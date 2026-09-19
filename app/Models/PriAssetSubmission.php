<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriAssetSubmission extends Model
{
    use HasFactory;

    protected $table = 'pri_asset_submissions';

    protected $fillable = [
        'gp_id',
        'status',
        'submitted_at',
        'submitted_by',
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
    ];

    /**
     * Gram Panchayat associated with this submission.
     */
    public function gp()
    {
        return $this->belongsTo(Gp_List::class, 'gp_id');
    }
}