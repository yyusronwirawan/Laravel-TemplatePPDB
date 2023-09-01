<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $table = 'blog';


    protected $fillable = [
        'judul', 'gambar', 'slug', 'kategori_id', 'kontent'
    ];

    public function kategori()
    {
        return $this->belongsTo(kategori::class, 'kategori_id', 'id');
    }

    public function tag(){
        return $this->hasMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id');
    }
}
