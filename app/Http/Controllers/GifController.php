<?php

namespace App\Http\Controllers;

use App\Helpers\GiphyHelper;
use App\Http\Requests\SaveRequest;
use App\Http\Requests\SearchByIdRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Http\Resources\GifsResource;
use App\Models\FavoriteGif;
use App\Models\Gif;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class GifController extends Controller
{
    //
    public function searchByName(SearchByNameRequest $request)
    {
        $q = $request->input('query');
        $limit = $request->input('limit', 10);
        $offset = $request->input('offset', 0);

        $gifs = GiphyHelper::searchByName($q, $limit, $offset);
        if (!$gifs) {
            return response()->json([
                'message' => 'Gifs not found'
            ], 404);
        }
        return response()->json(GifsResource::collection($gifs));
    }

    public function searchById(SearchByIdRequest $request)
    {
        $id = $request->input('id');
        $gifs = GiphyHelper::searchById($id);
        if (!$gifs) {
            return response()->json([
                'message' => 'Gif not found'
            ], 404);
        }
        return response()->json(new GifsResource($gifs));
    }

    public function save(SaveRequest $request)
    {
        $giphy = GiphyHelper::searchById($request->input('gif_id'));
        if (!$giphy) {
            return response()->json([
                'message' => 'Gif not found'
            ], 404);
        }
        $gif = Gif::createFromGiphy($giphy);
        if (!$gif) {
            return response()->json([
                'message' => 'Error saving gif'
            ], 500);
        }

        $favoriteGif = FavoriteGif::create(
            $request->input('alias'),
            $request->input('user_id'),
            $gif
        );

        if (!$favoriteGif) {
            return response()->json([
                'message' => 'Error saving gif'
            ], 500);
        }

        return response()->json([
            'message' => 'Gif saved successfully',
        ], 201);
    }

}
