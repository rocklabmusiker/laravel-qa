<?php

namespace App\Models;

use Parsedown;
use App\Models\User;
use App\Models\Question;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{
    use HasFactory;

    public function question() 
    {
        return $this->belongsTo(Question::class);
    }

    public function user() 
    {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute() 
    {
        $parsedown = new Parsedown();
        return $parsedown->text($this->body);
    }
}
