<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTypeVersion extends Model
{
    use HasFactory;
    // Add this to disable the updated_at timestamp
    public $timestamps = false;

    // Define which columns should have timestamps
    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'document_type_id',
        'version',
        'name',
        'created_at',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Get the document type that owns this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function documentType()
    {
        return $this->belongsTo(DocumentType::class);
    }

    /**
     * Get the user who updated this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }


}
