<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;
    
    protected $guarded = ['']; //delete_at업데이트 에러때문
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

