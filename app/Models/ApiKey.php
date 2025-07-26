<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
  protected $fillable = ['key', 'name', 'is_active'];

    /**
     * Generate a new API key.
     *
     * @return string
     */
    public static function generateKey()
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Scope a query to only include active API keys.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
