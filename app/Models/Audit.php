<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'url',
        'body',
        'response_code',
        'response_body',
        'ip'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function create($url, $body, $response_code, $response_body, $ip)
    {
        try{
            $audit = new Audit();
            $audit->user_id = session('user')->id;
            $audit->url = $url;
            $audit->body = $body;
            $audit->response_code = $response_code;
            $audit->response_body = json_encode($response_body);
            $audit->ip = $ip;
            $audit->save();

            return $audit->id;
        }
        catch(\Exception $e){
            return null;
        }
    }
}
