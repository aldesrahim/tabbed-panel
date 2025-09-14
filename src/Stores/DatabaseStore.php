<?php

namespace Aldesrahim\TabbedPanel\Stores;

use Aldesrahim\TabbedPanel\Context;
use Aldesrahim\TabbedPanel\Models\Tabs;
use Aldesrahim\TabbedPanel\Stores\Contracts\StoreContract;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class DatabaseStore implements StoreContract
{
    private Context $context;

    public function setContext(Context $context): void
    {
        $this->context = $context;
    }

    private function baseQuery(): Builder
    {
        return Tabs::onContext($this->context);
    }

    public function getTabs(): array
    {
        return $this->baseQuery()
            ->orderBy('tab_order')
            ->get()
            ->mapWithKeys(fn (Tabs $tabs) => [$tabs->tab_key => $tabs->toArray()])
            ->all();
    }

    public function addTab(array $tab, bool $activate = true): void
    {
        $tabKey = $tab['key'];

        $existing = $this->baseQuery()->where('tab_key', $tabKey)->first();

        if (null === $order = $existing?->tab_order) {
            $order = 1 + $this->baseQuery()->max('tab_order') ?? 0;
        }

        $newTab = Tabs::query()->updateOrCreate(
            attributes: [
                'user_id' => $this->context->userId,
                'tenant_id' => $this->context->tenantId,
                'tab_key' => $tabKey,
            ],
            values: [
                'tab_data' => $tab,
                'tab_order' => $order,
                'is_active' => $activate,
                'updated_at' => now(),
            ]
        );

        if ($activate) {
            $this->baseQuery()
                ->whereKeyNot($newTab->id)
                ->where('is_active', true)
                ->update(['is_active' => false]);
        }
    }

    public function hasTab(string $tabKey): bool
    {
        return $this->baseQuery()->where('tab_key', $tabKey)->exists();
    }

    public function removeTab(string $tabKey): void
    {
        $tabToRemove = $this->baseQuery()->where('tab_key', $tabKey)->first();

        if (! $tabToRemove) {
            return;
        }

        DB::transaction(function () use ($tabToRemove) {
            $this->baseQuery()
                ->where('tab_order', '>', $tabToRemove->tab_order)
                ->update(['tab_order' => DB::raw('tab_order - 1')]);

            if ($tabToRemove->is_active) {
                $nextTab = $this->baseQuery()
                    ->whereKeyNot($tabToRemove->id)
                    ->where('tab_order', '<=', $tabToRemove->tab_order)
                    ->orderByDesc('tab_order')
                    ->first();

                $nextTab?->update(['is_active' => 1]);
            }

            $tabToRemove->delete();
        });
    }

    public function getActiveTab(): ?string
    {
        return $this->baseQuery()
            ->where('is_active', true)
            ->value('tab_key');
    }

    public function setActiveTab(?string $tabKey): void
    {
        DB::transaction(function () use ($tabKey) {
            $this->baseQuery()->update(['is_active' => false]);

            if ($tabKey !== null) {
                $this->baseQuery()
                    ->where('tab_key', $tabKey)
                    ->update(['is_active' => true]);
            }
        });
    }

    public function getTabsOrder(): array
    {
        return $this->baseQuery()
            ->orderBy('tab_order')
            ->pluck('tab_key')
            ->toArray();
    }
}
