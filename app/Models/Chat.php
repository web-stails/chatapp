<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

/**
 * Созданные чаты.
 *
 * @property integer $id                    ID.
 * @property string $title                 название чата.
 * @property Carbon $created_at            Дата время создания.
 * @property Carbon $updated_at            Дата время обновления.
 */
class Chat extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
    ];


    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Является ли пользователь участником текущего чата.
     *
     * @param User $user
     *
     * @return bool
     */
    public function isUser(User $user): bool
    {
        return $this->users()->whereId($user->id)->exists();
    }

    /**
     * Список пользователей текущего чата.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserChat::class);
    }

    /**
     * Пользователь текущего чата (Какой из - определить дополнительным условием).
     *
     * @return BelongsToMany
     */
    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(
            User::class,
            UserChat::class,
            'chat_id',
            'id',
            'id',
            'user_id',
        );
    }

}
