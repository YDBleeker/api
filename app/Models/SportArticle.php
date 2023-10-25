<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SportArticle extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'image',
        'count',
    ];

    public function sport_article(){
        return $this->hasMany(Reservation::class, "sport_article_id", "id");
    }
}
