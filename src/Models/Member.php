<?php

namespace Wsmallnews\Member\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Wsmallnews\Comment\Models\Concerns\BeReplyer;
use Wsmallnews\Comment\Models\Concerns\Commenter;
use Wsmallnews\Preference\Models\Concerns\Preferencer;
use Wsmallnews\Preference\Models\Concerns\Preferencer\Follower;
use Wsmallnews\Preference\Models\Concerns\Preferencer\Liker;
use Wsmallnews\Preference\Models\Concerns\Preferencer\Viewer;
use Wsmallnews\Member\Enums\MemberStatus;
use Wsmallnews\Support\Casts\CounterCast;
use Wsmallnews\Support\Concerns\UserIdentifiable;
use Wsmallnews\Support\Contracts\HasSnIdentifiable;
use Wsmallnews\Support\Models\SupportModel;
use Wsmallnews\Support\Support\Utils as SupportUtils;
use Wsmallnews\User\Support\Utils as UserUtils;

class Member extends SupportModel implements HasSnIdentifiable
{
    use BeReplyer;
    use Commenter;
    use Follower;
    use Liker;
    use Preferencer;
    use SoftDeletes;
    use UserIdentifiable;
    use Viewer;

    protected $table = 'sn_members';

    protected $casts = [
        'counter' => CounterCast::class,
        'options' => 'array',
        'status' => MemberStatus::class,
    ];

    protected $appends = [
        'name',
        'email',
        'avatar_url',
    ];

    /**
     * 搜索字段（用于 morphFilter 关键词搜索）。
     */
    public static array $keywordSearchFields = ['name'];

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->name,
        );
    }

    protected function email(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->email,
        );
    }

    protected function avatarUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->user?->avatar_url,
        );
    }

    // public static function findOrCreateForTenant(Model $user, Model $tenant): static
    // {
    //     return static::firstOrCreate([
    //         'user_id' => $user->id,
    //         'team_id' => $tenant->id,
    //     ]);
    // }

    public function user(): BelongsTo
    {
        return $this->belongsTo(UserUtils::getUserModel());
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(SupportUtils::getTenantModel());
    }
}
