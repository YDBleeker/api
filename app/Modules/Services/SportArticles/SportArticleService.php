<?php

namespace App\Modules\Services\SportArticles;

use App\Models\SportArticle;
use App\Modules\Services\Service;

class SportArticleService extends Service
{

    protected $_rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'image' => 'required|string',
        'count' => 'required|integer|min:0',
    ];

    public function __construct(SportArticle $model)
    {
        parent::__construct($model);
    }

    //create createSportArticle($data) function
    public function createSportArticle($data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $model = $this->_model->create($data);
        return $model;
    }

}

