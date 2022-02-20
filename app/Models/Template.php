<?php

namespace App\Models;

use App\Templates\TemplateType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property TemplateType $slug
 * @property string $content
 * @property Carbon $created_at
 */
class Template extends Model
{
    protected $table = 'templates';
    public $timestamps = true;
}
