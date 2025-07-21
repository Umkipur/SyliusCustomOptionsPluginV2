<?php

declare(strict_types=1);

namespace Brille24\SyliusCustomerOptionsPlugin\Twig\Component;

use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;

#[AsLiveComponent(name: 'brille24:test_ping')]
final class TestPingComponent
{
    public function getPing(): string
    {
        return 'pong';
    }
}
