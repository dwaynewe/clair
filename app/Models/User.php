<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $fillable = ['name', 'email', 'external_id'];

    public function businesses(): BelongsToMany
    {
        return $this->belongsToMany(Business::class, null, 'user_ids', 'business_ids');
    }

    public function payItems(): HasMany
    {
        return $this->hasMany(PayItem::class);
    }
}
