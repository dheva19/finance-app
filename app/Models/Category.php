<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['user_id', 'name', 'type'])]
class Category extends Model
{

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
