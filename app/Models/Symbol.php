<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Symbol extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'code',
    ];

    /**
     * Get the exchange rates that belongs to the symbol.
     */
    public function exchange_rates()
    {
        return $this->hasMany(ExchangeRate::class);
    }
}
