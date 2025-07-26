<?php
// app/Models/GroupPost.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    use HasFactory;

    protected $fillable = ['group_id', 'user_id', 'content', 'image_url'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'group_id' => $this->group_id,
            'user_id' => $this->user_id,
            'content' => $this->content,
            'image_url' => $this->image_url ? asset('storage/' . $this->image_url) : null,
            'user' => $this->user,
            'created_at' => $this->created_at->toDateTimeString(),
        ];
    }
}
