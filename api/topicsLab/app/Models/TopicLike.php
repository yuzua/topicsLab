<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TopicLike extends Model
{
    use HasFactory;

    protected $fillable = ['topic_id','user_id'];

    public function topic()
    {
        return $this->belongsTo(Reply::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
