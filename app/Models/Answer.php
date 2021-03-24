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

    protected $fillable = [
        'body', 'user_id'
    ];

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

    public static function boot() 
    {
        parent::boot();

        static::created(function ($answer) {
           $answer->question->increment('answers_count');
        });

        static::deleted(function ($answer) {
            $question = $answer->question;
            $question->decrement('answers_count');

            if ($question->best_answer_id == $answer->id) {
                $question->best_answer_id = NULL;
                $question->save();
            }

        });

    }

    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute() 
    {
        return $this->id == $this->question->best_answer_id ? 'vote-accepted' : ''; 
    }
}
