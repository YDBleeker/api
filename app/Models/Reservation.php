<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reservation extends Model
{
    use HasFactory;
    protected $fillable = [
        'sport_article_id',
        'count',
        'name',
        'phone',
        'email',
        'course',
        'confirmed',
        'lent',
        'start_date',
        'end_date',
    ];

    public function sportarticle(){
        return $this->belongsTo(SportArticle::class, "sport_article_id", "id");
    }

}
