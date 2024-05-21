<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class FavoriteGif extends Model
{
    use HasFactory;

    protected $fillable = [
        'alias',
        'user_id',
        'gif_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function gif()
    {
        return $this->belongsTo(Gif::class);
    }

    public static function create($alias, $user_id, $gif_id)
    {
        try{
            $favorite = new FavoriteGif();
            $favorite->alias = $alias;
            $favorite->gif()->associate($gif_id);
            $favorite->user()->associate($user_id);
            $favorite->save();

            return $favorite->id;
        }
        catch(\Exception $e){
            Log::error($e->getMessage());
            return null;
        }
    }
}
