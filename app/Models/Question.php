<?php

namespace App\Models;

use Parsedown;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body'
    ];

    public function user() 
    {
        return $this->belongsTo(User::class);
    }
    
    // automatic fill fields slug
    // https://laravel.com/docs/8.x/eloquent-mutators
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getUrlAttribute() 
    {
        return route('questions.show', $this->slug);
    }

    // call this method in view like -> $queston->created_date
    // I can't use created_at, because of Attribute, first characters begin with At
    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function getStatusAttribute() 
    {
        if ($this->answers > 0) {
            if ($this->best_answer_id) {
                return 'answered-accepted';
            }
            return 'answered';
        } 
        return 'unanswered';
    }

    public function getBodyHtmlAttribute() 
    {
        $parsedown = new Parsedown();
        return $parsedown->text($this->body);
    }

}
