<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Business extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'businesses';
    protected $fillable = ['name', 'external_id', 'enabled', 'deduction_percentage'];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, null, 'business_ids', 'user_ids');
    }

    public function payItems(): HasMany
    {
        return $this->hasMany(PayItem::class);
    }
}
