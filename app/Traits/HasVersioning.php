<?php

namespace App\Traits;

trait HasVersioning
{
    /**
     * Get all versions for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function versions()
    {
        return $this->hasMany(get_class($this) . 'Version');
    }

    /**
     * Get the current active version for this model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentVersion()
    {
        return $this->belongsTo(get_class($this) . 'Version', 'current_version_id');
    }

    /**
     * Get the latest version for this model.
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function getLatestVersionAttribute()
    {
        return $this->versions()->latest('version')->first();
    }
}
