<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExchangeRate extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'rate_date',
        'symbol_id',
        'rate_value',
        'symbol_code',
    ];

    /**
     * Get the symbol that owns to the exchange rate.
     */
    public function symbol()
    {
        return $this->belongsTo(Symbol::class);
    }
}
