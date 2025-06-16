<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\HasVersioning;

class DocumentType extends Model
{
    use HasFactory, HasVersioning;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_active',
        'current_version_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the documents of this type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function documents()
    {
        return $this->hasMany(Document::class, 'type_id');
    }

    /**
     * Get the template associated with this document type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function template()
    {
        return $this->hasOne(Template::class);
    }

    /**
     * Get the number format associated with this document type.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function numberFormat()
    {
        return $this->hasOne(NumberFormat::class);
    }
}
