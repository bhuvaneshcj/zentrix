<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'content',
        'is_pinned',
        'is_archived',
        'is_favorite',
        'is_locked',
        'color',
        'word_count',
        'character_count',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_archived' => 'boolean',
        'is_favorite' => 'boolean',
        'is_locked' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(NoteCategory::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(NoteTag::class, 'note_note_tag', 'note_id', 'tag_id');
    }
}
