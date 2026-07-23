<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'name', 'amount', 'is_primary'])]
class Pocket extends Model
{
    public function user(){
        return $this->belongsTo(User::class);
    }
}
