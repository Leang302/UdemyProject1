<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Post extends Model
{
    use Searchable;
    use HasFactory;
    protected $fillable = ['title', 'body', 'user_id'];
    
    //the name need to be toSearchableArray
    public function toSearchableArray()
    {
        return [
            'title'=>$this->title,
            'body'=>$this->body,
        ];
    }
    public function user()
    {
        return $this->belongsTo(user::class, 'user_id');
    }
}
