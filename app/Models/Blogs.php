<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Facades\Storage;

class blogs extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'text', 'user_id', 'status', 'tag1', 'tag2', 'tag3', 'cover_image'];


    public function comments(){
        return $this->hasMany(Comments::class);
    }
    
    public function users(){
        return $this->belongsTo(User::class);
    } 

    public function getCoverImageUrlAttribute(){
        return $this->cover_image ? Storage::url($this->cover_image) : null;
    }


}
