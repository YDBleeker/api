<?php

namespace App\Modules\Services\SportArticles;

use App\Models\SportArticle;
use App\Modules\Services\Service;

class SportArticleService extends Service
{

    protected $_rules = [
        'name' => 'required|string|max:255',
        'description' => 'required|string',
        'count' => 'required|integer|min:0',
        'max_reservation_days' => 'required|integer|min:1',
    ];

    public function __construct(SportArticle $model)
    {
        parent::__construct($model);
    }

    public function getSportArticles()
    {
        $sportArticles = $this->_model->get();

        foreach ($sportArticles as $sportArticle) {
            $sportArticle->image = url(route('image', ['name' => $sportArticle->image]));
        }

        return $sportArticles;
    }


    public function getSportArticlesById($id)
    {
        $sportArticle = $this->_model->find($id);

        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        $sportArticle->image = url(route('image', ['name' => $sportArticle->image]));

        return $sportArticle;
    }

    public function createSportArticle($data, $image)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $sportArticle = $this->_model->where('name', $data['name'])->first();
        if ($sportArticle) {
            $this->_errors->add('name', 'Name already exists');
            return;
        }

        $extension = $image->getClientOriginalExtension();

        $imageName = $data['name'] . '.' . $extension;
        $image->storeAs('sport-articles', $imageName);

        $data['image'] = $imageName;

        $model = $this->_model->create($data);
        return $model;
    }

    public function updateSportArticle($id, $data)
    {
        $this->validate($data);
        if ($this->hasErrors()) {
            return;
        }

        $sportArticle = $this->_model->find($id);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        $sportArticle->update($data);
        return $sportArticle;
    }

    public function deleteSportArticle($id)
    {
        $sportArticle = $this->_model->find($id);
        if (!$sportArticle) {
            $this->_errors->add('sport_article_id', 'Sport article not found');
            return;
        }

        $sportArticle->delete();
        return 'Sport article deleted';
    }

}

