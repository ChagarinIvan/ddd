<?php

namespace App\Models;

use App\Templates\TemplateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $recipient
 * @property TemplateType $channel
 * @property string $body
 * @property bool $dispatched
 * @property Carbon $created_at
 */
class Notification extends Model
{
    protected $table = 'notifications';
    public $timestamps = true;
}
