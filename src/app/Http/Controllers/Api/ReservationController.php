<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReservationController extends Controller
{
    /**
     * 指定期間の予約一覧を返す
     */
    public function index(Request $request)
    {
        // 1. 期間取得 (デフォルト: 今日〜1ヶ月後)
        $start = $request->query('start') ? Carbon::parse($request->query('start')) : Carbon::today();
        $end = $request->query('end') ? Carbon::parse($request->query('end')) : Carbon::today()->addMonth();

        // 2. 予約取得 (期間重複チェック)
        $reservations = Reservation::with(['user', 'resource'])
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->get();

        // 3. v-calendar用フォーマットへ変換
        return $reservations->map(function ($reservation) {
            return [
                'id' => $reservation->id,
                'title' => $reservation->user->name ?? 'Unknown',
                'start' => $reservation->start_at->format('Y-m-d H:i'),
                'end' => $reservation->end_at->format('Y-m-d H:i'),
                // categoryプロパティにリソース名をセットすることで紐付けます
                'category' => $reservation->resource->name,
                
                // その他制御用
                'resource_id' => $reservation->resource_id,
                'user_id' => $reservation->user_id,
                'timed' => true,
                'color' => $reservation->is_confirmed ? 'blue' : 'orange',
            ];
        });
    }
}