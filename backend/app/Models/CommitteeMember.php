<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommitteeMember extends Model
{
    // Disable timestamps since your migration doesn't include them
    
    protected $table = 'committee_member';

    protected $fillable = [
        'full_name',
        'committee',
        'role',
        'from_etablissement',
        'image_url',
        'edition_id',
        'email',
        'phone',
    ];

    public function edition()
    {
        return $this->belongsTo(Edition::class, 'edition_id');
    }
}