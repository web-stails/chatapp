<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Сообщения чатов.
 *
 * @property integer $id                    ID.
 * @property integer $user_id               Автор сообщения.
 * @property integer $chat_id               чат сообщения.
 * @property string  $text                  сообщение.
 * @property Carbon  $created_at            Дата время создания.
 * @property Carbon  $updated_at            Дата время обновления.
 */
class Message extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'chat_id',
        'text',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'user_id'    => 'integer',
            'chat_id'    => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(Chat::class);
    }

    public function isUser(User $user): bool
    {
        return $this->chat()->isUser($user);
    }
}
