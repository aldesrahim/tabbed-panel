<?php

namespace Aldesrahim\TabbedPanel\Database\Factories;

use Aldesrahim\TabbedPanel\Models\Tabs;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ModelFactory extends Factory
{
    protected $model = Tabs::class;

    protected static ?int $userId = null;

    protected static ?int $tenantId = null;

    public static function getUserId(): int
    {
        return self::$userId ??= 1;
    }

    public static function getTenantId(): int
    {
        return self::$tenantId ??= 1;
    }

    public function configure(): static
    {
        return $this->afterMaking(function (Tabs $tabs) {
            $maxOrder = Tabs::query()
                ->where('user_id', $tabs->user_id)
                ->where('tenant_id', $tabs->tenant_id)
                ->where('tab_order', '>', 0)
                ->max('tab_order') ?? 0;

            $tabs->update(['tab_order' => 1 + $maxOrder]);
        });
    }

    public function definition(): array
    {
        return [
            'user_id' => self::getUserId(),
            'tab_key' => $key = hash('xxh128', Str::random()),
            'tab_data' => [
                'key' => $key,
            ],
            'tab_order' => 0,
        ];
    }

    public function tenant(): static
    {
        return $this->state(fn () => [
            'tenant_id' => self::getTenantId(),
        ]);
    }
}
