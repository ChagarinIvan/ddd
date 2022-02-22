<?php

namespace App\Models;

use App\Verifications\Subject;
use App\Verifications\SubjectType;
use App\Verifications\UserInfo;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * @property string $id
 * @property string $identity
 * @property SubjectType $type
 * @property bool $confirmed
 * @property int $code
 * @property ?string $ip
 * @property ?string $agent
 * @property int $attempt
 * @property Carbon $created_at
 * @property Subject $subject
 * @property UserInfo $userInfo
 * @property bool $isExpire
 *
 * @method static Verification|null find(string $id)
 * @method static Collection get()
 * @method static Verification whereIdentity(string $identity)
 * @method static Verification whereType(SubjectType $type)
 */
class Verification extends Model
{
    protected $table = 'verifications';
    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->{$model->getKeyName()})) {
                $model->{$model->getKeyName()} = Str::uuid()->toString();
            }
        });
    }

    public function getIncrementing(): bool
    {
        return false;
    }

    public function getKeyType(): string
    {
        return 'string';
    }

    protected function subject(): Attribute
    {
        return new Attribute(
            get: fn ($verification) => new Subject($verification->identity, $verification->type),
            set: fn (Subject $subject) => [
                'identity' => $subject->identity,
                'type' => $subject->type,
            ],
        );
    }

    protected function userInfo(): Attribute
    {
        return new Attribute(
            get: fn ($verification) => new UserInfo($verification->ip, $verification->agent),
            set: fn (UserInfo $userInfo) => [
                'ip' => $userInfo->ip,
                'agent' => $userInfo->agent,
            ],
        );
    }

    protected function isExpire(): Attribute
    {
        return new Attribute(
            get: fn ($verification) => $this->attempt >= 5 || Carbon::now()->diffInMinutes($this->created_at) >= 5,
        );
    }
}
