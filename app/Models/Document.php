<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'doc_name',
        'doc_title',
        'doc_upload',
        'description',
        'category_id',
        'user_id',
    ];

    // Document belongs to a Category
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Document belongs to a User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
