<?php
declare(strict_types=1);

namespace Brille24\SyliusCustomerOptionsPlugin\Twig\Component\CustomerOption;

use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
use Symfony\UX\LiveComponent\Attribute\LiveProp;

trait LiveChannelPropTrait {
    #[LiveProp(
        hydrateWith: 'hydrateChannel',
        dehydrateWith: 'dehydrateChannel',
        fieldName: 'channel'
    )]
    public ?ChannelInterface $channel = null;

    protected ChannelRepositoryInterface $channelRepository;

    /**
     * Called from your component constructor.
     */
    protected function initializeChannel(ChannelRepositoryInterface $channelRepository): void
    {
        $this->channelRepository = $channelRepository;
    }

    public function hydrateChannel(mixed $code): ?ChannelInterface
    {
        if (null === $code) {
            return null;
        }

        return $this->channelRepository->findOneBy(['code' => $code]);
    }

    public function dehydrateChannel(?ChannelInterface $channel): mixed
    {
        return $channel?->getCode();
    }
}