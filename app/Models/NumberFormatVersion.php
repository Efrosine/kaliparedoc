<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NumberFormatVersion extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'number_format_id',
        'version',
        'format_string',
        'created_at',
        'updated_by'
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get the number format that owns this version.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function numberFormat()
    {
        return $this->belongsTo(NumberFormat::class);
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
