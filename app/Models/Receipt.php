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
    public static function paginateAndSearch($q)
    {

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
            ->paginate(5);

    }

    /**
     * Get months that have receipts
     *
     * @param int $skip
     * @param int $take
     * @return array
     */
    public static function months($skip=0, $take=6)
    {

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
    public static function monthCount()
    {

        $months = Receipt::select(DB::raw('DATE_FORMAT(date, "%Y-%m") as date'))
            ->distinct('date')
            ->orderBy('date', 'desc')
            ->get();

        return ($months) ? count($months->pluck('date')->all()) : 0;

    }

    /**
     * Fetches all user receipts cost per month
     *
     * @param $month
     * @param $users
     * @return mixed
     */
    public static function sumUserAmountByMonth($month, $users)
    {

        $results = [];

        $rows = Receipt::select([
                'users.id',
                'users.name',
                'users.color',
                DB::raw('ROUND(SUM(receipts.amount), 2) AS sum')
            ])
            ->join('users', 'users.id', '=', 'receipts.user_id')
            ->where('receipts.date', 'LIKE', $month.'%')
            ->groupBy([DB::raw('DATE_FORMAT(receipts.date, "%Y-%m")'), 'receipts.user_id'])
            ->orderBy('sum', 'desc')
            ->get();

        if($rows) {

            $results = $rows->all();

            foreach ($results as $row) {
                $index = array_search($row['id'], $users);
                if($index !== false) unset($users[$index]);
            }

            foreach ($users as $id) {
                $user = User::find($id);
                array_push($results, [
                    'name' => $user->name,
                    'color' => $user->color,
                    'sum' => 0
                ]);
            }

        }

        return ($results) ?: false;

    }

    /**
     *
     *
     * @param $month
     * @return false
     */
    public static function sumAmountByMonth($month)
    {

        $rows = Receipt::select(DB::raw('ROUND(SUM(receipts.amount), 2) AS total'))
            ->where('receipts.date', 'LIKE', $month.'%')
            ->groupBy(DB::raw('DATE_FORMAT(receipts.date, "%Y-%m")'))
            ->get();

        return ($rows) ? $rows->pluck('total')[0] : false;

    }

    /**
     * Calculate compensation payments
     *
     * @param $total
     * @param $amounts
     * @param $names
     * @return array
     */
    public static function calcPayments($total, $amounts, $names)
    {

        $calc_result = [];
        $senders = [];
        $recipients = [];

        $average = $total / count($amounts);

        $fi = 0;
        foreach ($amounts as $amount) {

            $needed = $average - $amount;
            $person = ['name' => $names[$fi], 'amount' => $needed];

            if($needed < 0) array_push($recipients, $person);
            elseif($needed !== 0) array_push($senders, $person);

            $fi++;

        }

        if(!$senders OR !$recipients) return [];

        usort($senders, function ($a, $b) {
            return ( $a['amount'] < $b['amount']) ? 1 : -1;
        });

        usort($recipients, function ($a, $b) {
            return ($a['amount'] > $b['amount']) ? -1 : 1;
        });

        while(count($senders)) {

            $sender_key = array_key_first($senders);
            $sender_name = $senders[$sender_key]['name'];

            $recipient_key = array_key_first($recipients);
            $recipient_name = $recipients[$recipient_key]['name'];

            $pre_sum = round($recipients[$recipient_key]['amount'] + $senders[$sender_key]['amount'], 2);

            if($pre_sum < -0.01) {

                $recipients[$recipient_key]['amount'] = $pre_sum;
                $final_sum = $senders[$sender_key]['amount'];
                unset($senders[$sender_key]);

            } elseif($pre_sum > 0.01) {

                $diff_sum = round($senders[$sender_key]['amount'] - abs($recipients[$recipient_key]['amount']), 2);

                if($diff_sum) {
                    $final_sum = $senders[$sender_key]['amount'] - $diff_sum;
                    $senders[$sender_key]['amount'] = $diff_sum;
                } else {
                    $final_sum = $senders[$sender_key]['amount'];
                    unset($senders[$sender_key]);
                }

                unset($recipients[$recipient_key]);

            } else {

                $final_sum = $senders[$sender_key]['amount'];
                unset($senders[$sender_key]);
                unset($recipients[$recipient_key]);

            }

            if($final_sum) {

                array_push($calc_result, [
                    'sender' => $sender_name,
                    'sum' => round($final_sum, 2),
                    'recipient' => $recipient_name,
                ]);

            }

        }

        return $calc_result;

    }

    /**
     * Destroy deleted entries and unlink images
     *
     * @return bool
     */
    public static function destroyAll()
    {

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
    public function users()
    {

        return $this->belongsTo(Receipt::class);

    }


}
