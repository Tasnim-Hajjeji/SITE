<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    // Disable timestamps since your migration doesn't include them
    public $timestamps = false;
    
    protected $table = 'committee_member';

    protected $fillable = [
        'full_name',
        'committee',
        'role',
        'from',
        'image_url',
        'edition_id',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}