<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
        'start_at',
        'end_at',
        'is_confirmed',
    ];

    /**
     * キャスト設定
     * フロントエンドへのJSON出力時に秒をカットするフォーマットを指定
     */
    protected $casts = [
        'start_at' => 'datetime:Y-m-d H:i',
        'end_at' => 'datetime:Y-m-d H:i',
        'is_confirmed' => 'boolean',
    ];

    /**
     * 予約は一人のユーザーに属する
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 予約は一つのリソースに属する
     */
    public function resource(): BelongsTo
    {
        return $this->belongsTo(Resource::class);
    }
}