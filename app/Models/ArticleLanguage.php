<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleLanguage extends Model
{
    protected $table = 'article_languages';

    protected $fillable = ['language', 'title', 'description', 'seo_title', 'seo_description'];
}
