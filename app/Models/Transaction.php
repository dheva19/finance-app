<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable('transaction_number', 'user_id', 'from_pocket_id', 'to_pocket_id', 'category_id', 'amount', 'type', 'note')]
class Transaction extends Model
{
    public function fromPocket(){
        return $this->belongsTo(Pocket::class, 'from_pocket_id');
    }

    public function toPocket(){
        return $this->belongsTo(Pocket::class, 'to_pocket_id');
    }

    public function category(){
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getTypeStyle(){
        $type = $this->type;
        $classStyle = "";

        if($type == "income"){
            $classStyle .= "px-2 py-0.5 bg-green-500 text-white rounded-full uppercase text-xs font-semibold";
        }else if( $type == "expense"){
            $classStyle .= "px-2 py-0.5 bg-red-500 text-white rounded-full uppercase text-xs font-semibold";
        }else if( $type == "transfer"){
            $classStyle .= "px-2 py-0.5 bg-blue-500 text-white rounded-full uppercase text-xs font-semibold";
        }
        return $classStyle;
    }
}
