<?php

namespace Aldesrahim\TabbedPanel;

class Context
{
    public function __construct(
        public readonly int | string $userId,
        public readonly int | string | null $tenantId = null,
    ) {}
}
