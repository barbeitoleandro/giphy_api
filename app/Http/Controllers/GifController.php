<?php

namespace App\Http\Controllers;

use App\Helpers\GiphyHelper;
use App\Http\Requests\SaveRequest;
use App\Http\Requests\SearchByIdRequest;
use App\Http\Requests\SearchByNameRequest;
use App\Http\Resources\GifsResource;
use App\Models\Audit;
use App\Models\FavoriteGif;
use App\Models\Gif;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

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
            $response_code = 404;
            $response_body = [
                'message' => 'Gifs not found'
            ];

            return $this->responseFunction(
                $response_code,
                $response_body,
                $request
            );
        }

        $response_code = 200;
        $response_body = GifsResource::collection($gifs);

        return $this->responseFunction(
            $response_code,
            $response_body,
            $request
        );


    }

    public function searchById(SearchByIdRequest $request)
    {
        $id = $request->input('id');
        $gifs = GiphyHelper::searchById($id);
        if (!$gifs) {
            $response_code = 404;
            $response_body = [
                'message' => 'Gif not found'
            ];

            return $this->responseFunction(
                $response_code,
                $response_body,
                $request
            );
        }


        $response_code = 200;
        $response_body = new GifsResource($gifs);

        return $this->responseFunction(
            $response_code,
            $response_body,
            $request
        );
    }

    public function save(SaveRequest $request)
    {
        $giphy = GiphyHelper::searchById($request->input('gif_id'));
        if (!$giphy) {
            $response_code = 404;
            $response_body = [
                'message' => 'Gif not found'
            ];

            return $this->responseFunction(
                $response_code,
                $response_body,
                $request
            );
        }
        $gif = Gif::createFromGiphy($giphy);
        if (!$gif) {
            $response_code = 500;
            $response_body = [
                'message' => 'Error saving gif'
            ];

            return $this->responseFunction(
                $response_code,
                $response_body,
                $request
            );
        }

        $favoriteGif = FavoriteGif::create(
            $request->input('alias'),
            $request->input('user_id'),
            $gif
        );

        if (!$favoriteGif) {
            $response_code = 500;
            $response_body = [
                'message' => 'Error saving favorite gif'
            ];

            return $this->responseFunction(
                $response_code,
                $response_body,
                $request
            );
        }

        $response_code = 200;
        $response_body = [
            'message' => 'Gif saved successfully'
        ];

        return $this->responseFunction(
            $response_code,
            $response_body,
            $request
        );
    }

    public function responseFunction($response_code, $response_body, $request){

        Audit::create(
            $request->url(),
            $request->getContent(),
            $response_code,
            $response_body,
            $request->ip()
        );

        return response()->json($response_body, $response_code);

    }


}
