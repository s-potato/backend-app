<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Task extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'description', 'is_done', 'user_id'];

    public function creater(){
        return $this->belongsTo(User::class, 'creater_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }
}
