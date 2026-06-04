<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'subject',
        'message',
        'related_id',
        'related_type',
        'is_global',
        'url',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notification_user')
            ->withPivot('is_read')
            ->withTimestamps();
    }

    public function readByUsers()
    {
        return $this->users()->wherePivot('is_read', true);
    }

    public function unreadByUsers()
    {
        return $this->users()->wherePivot('is_read', false);
    }
}

// $notification = Notification::create([
//     'type' => 'General',
//     'subject' => 'System Update',
//     'message' => 'The system will be down for maintenance tonight.',
//     'is_global' => true
// ]);

// // Attach all users
// $users = User::all();
// foreach ($users as $user) {
//     $user->notifications()->attach($notification->id, ['is_read' => false]);
// }
