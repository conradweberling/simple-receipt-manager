<?php

namespace App\Models;

use App\Events\ReceiptDestroyed;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Receipt extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'image',
        'thumbnail',
        'amount',
        'date'
    ];

    /**
     * Search
     *
     * @param $q
     * @return mixed
     */
    public static function paginateAndSearch($q) {

        return self
            ::select(
                'receipts.id',
                'receipts.amount',
                'receipts.date',
                'users.name',
                'receipts.image',
                'receipts.thumbnail'
            )
            ->join('users', 'users.id', '=', 'receipts.user_id')
            ->where('receipts.amount', 'LIKE', '%'.$q.'%')
            ->orWhere('receipts.date', 'LIKE', '%'.$q.'%')
            ->orWhere('users.name', 'LIKE', '%'.$q.'%')
            ->orderBy('receipts.date', 'desc')
            ->orderBy('receipts.created_at', 'desc')
            ->paginate(3);

    }

    /**
     * Get months that have receipts
     *
     * @param int $skip
     * @param int $take
     * @return array
     */
    public static function months($skip=0, $take=6) {

        $months = Receipt::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as date'))
            ->distinct('date')
            ->orderBy('date', 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        return ($months) ? $months->pluck('date')->all() : [];

    }

    /**
     * Get month count
     *
     * @return mixed
     */
    public static function monthCount() {

        $months = Receipt::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as date'))
            ->distinct('date')
            ->orderBy('date', 'desc')
            ->get();

        return ($months) ? count($months->pluck('date')->all()) : 0;

    }

    /**
     *
     *
     * @param $month
     * @return false
     */
    public static function sumUserAmountByMonth($month) {

        $rows = Receipt::select(['users.name', 'users.color', DB::raw('ROUND(SUM(receipts.amount), 2) AS sum')])
            ->join('users', 'users.id', '=', 'receipts.user_id')
            ->where('receipts.date', 'LIKE', $month.'%')
            ->groupBy([DB::raw('DATE_FORMAT(receipts.date, "%Y-%m")'), 'receipts.user_id'])
            ->orderBy('sum', 'desc')
            ->get();

        return ($rows) ? $rows->all() : false;

    }



    /**
     *
     *
     * @param $month
     * @return false
     */
    public static function sumAmountByMonth($month) {

        $rows = Receipt::select(DB::raw('ROUND(SUM(receipts.amount), 2) AS total'))
            ->where('receipts.date', 'LIKE', $month.'%')
            ->groupBy(DB::raw('DATE_FORMAT(receipts.date, "%Y-%m")'))
            ->get();

        return ($rows) ? $rows->pluck('total')[0] : false;

    }

    /**
     * Destroy deleted entries and unlink images
     *
     * @return bool
     */
    public static function destroyAll() {

        $receipts = self::onlyTrashed()->where(
            'deleted_at', '<', now()->subDays(config('app.destroy_after_days'))
        )->get();

        foreach ($receipts as $receipt) {

            $files = [storage_path('app/'.$receipt->image), storage_path('app/'.$receipt->thumbnail)];
            foreach ($files as $file) if(is_file($file) AND strpos($file, 'dummy') === false) unlink($file);

            $receipt->forceDelete();

            event(new ReceiptDestroyed($receipt->user_id, $receipt->date, $receipt->amount));

        }

        return true;

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {

        return $this->belongsTo(Receipt::class);

    }


}
