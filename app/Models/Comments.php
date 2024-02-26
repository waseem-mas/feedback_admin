<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comments extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'feedback_id',
        'parent_id',
        'body'
    ];

    public function replies(){
        return $this->hasMany(Comments::class, 'parent_id');
    }
}
