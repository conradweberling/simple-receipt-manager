<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $primaryKey = 'email';
    public $incrementing = false;

    protected $fillable = [
        'email',
        'user_id',
        'token'
    ];

    protected $hidden = [
        'token'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user() {

        return $this->belongsTo(User::class);

    }

}
