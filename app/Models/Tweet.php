<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'image_path',
        'user_id',
    ];  

    public function user(){
        return $this->belongsTo(User::class);
    }

    /**
     * Determine the image path to the tweet.
     *
     * @return string
     */

    public function getImagePathAttribute($image)
    {                
        if ($image===null){
            return null;
        }

        if (strpos($image, 'via.')){
            return $image;
        }
        
        return asset('storage/'.$image);
        
    }
}
