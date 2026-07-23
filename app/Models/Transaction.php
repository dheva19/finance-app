<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('transaction_number', 'user_id', 'pocket_id', 'category_id', 'amout', 'type', 'note')]
class Transaction extends Model
{
    //
}
