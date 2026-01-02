<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'kind',
    ];

    /**
     * リソースは複数の予約を持つ
     */
    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
