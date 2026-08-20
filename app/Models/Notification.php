<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'link',
        'is_read'
    ];

    protected $casts = [
        'is_read' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Send notification to a specific user.
     */
    public static function sendToUser($userId, $title, $message, $link = null)
    {
        return self::create([
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'link' => $link,
            'is_read' => false
        ]);
    }

    /**
     * Send notification to all users.
     */
    public static function sendToAll($title, $message, $link = null)
    {
        $users = User::all();
        foreach ($users as $user) {
            self::create([
                'user_id' => $user->id,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'is_read' => false
            ]);
        }
    }
}
