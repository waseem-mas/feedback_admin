<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Categories;

class Feedback extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'title',
        'category_id',
        'body',
    ];

    public function category(){
        return $this->belongsTo(Categories::class,'category_id');
    }
}
