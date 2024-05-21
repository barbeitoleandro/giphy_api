<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Gif extends Model
{
    use HasFactory;

    protected $fillable = [
        'giphy_id',
        'url',
        'title'
    ];

    public function favorites()
    {
        return $this->belongsToMany(FavoriteGif::class);
    }

    public static function createFromGiphy($giphy)
    {
        try{
            $gif = new Gif();
            $gif->giphy_id = $giphy->id;
            $gif->title = $giphy->title;
            $gif->url = $giphy->url;
            $gif->save();

            return $gif->id;
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return null;
        }
    }
}
