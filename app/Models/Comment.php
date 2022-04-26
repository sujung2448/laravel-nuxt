<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $guarded = ['']; //delete_at업데이트 에러때문
    protected $table = 'comment'; //db에서 comments로 작성을 안해서 수동으로 지정
    
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function board()
    {
        return $this->belongsTo('App\Models\Board');
    }
}
