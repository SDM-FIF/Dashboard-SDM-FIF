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
        // Map notification context to setting features keys
        $fitur = 'notif_master';
        if (in_array($title, ['Data Baru', 'Perubahan Data', 'Informasi Baru'])) {
            $msgLower = strtolower($message);
            if (str_contains($msgLower, 'dosen') && !str_contains($msgLower, 'calon dosen')) {
                $fitur = 'notif_dosen';
            } elseif (str_contains($msgLower, 'surat')) {
                $fitur = 'notif_dosen';
            } elseif (str_contains($msgLower, 'tpa')) {
                $fitur = 'notif_tpa';
            } elseif (str_contains($msgLower, 'calon') || str_contains($msgLower, 'jadwal pengujian') || str_contains($msgLower, 'penilaian') || str_contains($msgLower, 'berita acara')) {
                $fitur = 'notif_rekrutasi';
            } elseif (str_contains($msgLower, 'mahasiswa') || str_contains($msgLower, 'kompetisi') || str_contains($msgLower, 'prestasi')) {
                $fitur = 'notif_mahasiswa';
            }
        }

        // Check if the notification is enabled for this feature
        if (!\App\Models\PengaturanNotifikasi::isEnabled($fitur)) {
            return;
        }

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
