<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
     use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'user_id',
    ];

      // Category belongs to a User (creator)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Category has many Documents
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
