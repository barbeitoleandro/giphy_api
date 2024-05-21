<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class GiphyHelper
{
    public static function searchByName($q, $limit = 10, $offset = 0)
    {
        try{
            $client = new Client();
            $response = $client->get('api.giphy.com/v1/gifs/search', [
                'query' => [
                    'api_key' => env('GIPHY_API_KEY'),
                    'q' => $q,
                    'limit' => $limit,
                    'offset' => $offset
                ]
            ]);

            $response = json_decode($response->getBody()->getContents());
            $gifs = $response->data;
            return $gifs;
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return null;
        }
    }

    public static function searchById($id)
    {
        try{
            $client = new Client();
            $response = $client->get('api.giphy.com/v1/gifs/' . $id, [
                'query' => [
                    'api_key' => env('GIPHY_API_KEY')
                ]
            ]);

            $response = json_decode($response->getBody()->getContents());
            $gifs = $response->data;
            return $gifs;
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return null;
        }
    }
}
