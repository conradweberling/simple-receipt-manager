<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    use HasFactory;

    protected $primaryKey = 'email';
    protected $fillable = ['email', 'user_id','token'];

    public function user() {

        return $this->belongsTo(User::class);

    }

}
