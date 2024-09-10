<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PayItem extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'pay_items';
    protected $fillable = ['external_id', 'hours_worked', 'pay_rate', 'pay_date', 'amount'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
