<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

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
            ::select('receipts.amount', 'receipts.date', 'users.name', 'receipts.image', 'receipts.thumbnail')
            ->join('users', 'users.id', '=', 'receipts.user_id')
            ->where('receipts.amount', 'LIKE', '%'.$q.'%')
            ->orWhere('receipts.date', 'LIKE', '%'.$q.'%')
            ->orWhere('users.name', 'LIKE', '%'.$q.'%')
            ->orderBy('receipts.date', 'desc')
            ->orderBy('receipts.created_at', 'desc')
            ->paginate(3);

    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function users() {

        return $this->belongsTo(Receipt::class);

    }


}
