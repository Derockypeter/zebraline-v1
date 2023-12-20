<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    use HasFactory;

    protected $fillable = ['url', 'imageable_id', 'imageable_type', 'created_at', 'deleted_at', 'updated_at'];

    /**
     * Get the parent imageable model (product or blog).
     */
    public function imageable()
    {
        return $this->morphTo();
    }
}
