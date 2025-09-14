<?php

namespace Aldesrahim\TabbedPanel\Models;

use Aldesrahim\TabbedPanel\Context;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property int $tenant_id
 * @property string $tab_key
 * @property array $tab_data
 * @property int $tab_order
 * @property bool $is_active
 *
 * @mixin \Eloquent
 */
class Tabs extends Model
{
    protected $table = 'tabbed_panel_tabs';

    protected $fillable = [
        'user_id',
        'tenant_id',
        'tab_key',
        'tab_data',
        'tab_order',
        'is_active',
    ];

    protected $casts = [
        'tab_data' => 'encrypted:array',
        'is_active' => 'boolean',
    ];

    public function scopeOnContext(Builder $query, Context $context): Builder
    {
        return $query->where(
            fn ($query) => $query
                ->where('user_id', $context->userId)
                ->where('tenant_id', $context->tenantId)
        );
    }
}
