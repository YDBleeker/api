<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Modules\Services\SportArticles\SportArticleService;

class SportArticleController extends Controller
{
    private $_sportArticleService;

    public function __construct(SportArticleService $sportArticleService)
    {
        $this->_sportArticleService = $sportArticleService;
    }

    public function detail($id)
    {
        $sportArticle = $this->_sportArticleService->getSportArticlesById($id);

        if (!$sportArticle) {
            return response()->json(['message' => 'SportArticle not found'], 404);
        }

        return response()->json($sportArticle);
    }

    public function all(Request $request)
    {
        $sportArticles = $this->_sportArticleService->getSportArticles();

        return response()->json($sportArticles);
    }

    public function create(Request $request)
    {
        $jsonData = json_decode($request->input('json_data'), true);
        $image = $request->file('image');
        $result = $this->_sportArticleService->createSportArticle($jsonData, $image);

        if ($this->_sportArticleService->hasErrors()) {
            return response()->json($this->_sportArticleService->getErrors(), 400);
        }

        return response()->json($result);
    }

    public function update(Request $request, $id)
    {
        $data = $request->only(['name', 'description', 'price', 'image', 'count', 'max_reservation_days']);
        $result = $this->_sportArticleService->updateSportArticle($id, $data);

        if ($this->_sportArticleService->hasErrors()) {
            return response()->json($this->_sportArticleService->getErrors(), 400);
        }

        return response()->json($result);
    }

    public function delete($id)
    {
        $message = $this->_sportArticleService->deleteSportArticle($id);

        return response()->json($message);
    }

    public function downloadImage($name)
    {

        $path = storage_path('app/sport-articles/' . $name);

        if (!file_exists($path)) {
            return response()->json(['message' => 'Image not found'], 404);
        }

        return response()->download($path);
    }
}
