<?php
declare(strict_types=1);

namespace Brille24\SyliusCustomerOptionsPlugin\Twig\Component\CustomerOption;

use Brille24\SyliusCustomerOptionsPlugin\Entity\CustomerOptions\CustomerOptionInterface;
use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Resource\Doctrine\Persistence\RepositoryInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Sylius\Component\Core\Repository\ChannelRepositoryInterface;
class FormComponent {

    use LiveCollectionTrait;

    /** @use ResourceFormComponentTrait<CustomerOptionInterface> */
    use ResourceFormComponentTrait {
        initialize as public __construct;
    }

    use TemplatePropTrait;
    use LiveChannelPropTrait;

    #[LiveProp(useSerializerForHydration: true)]
    public array $formValues = [];

    public function __construct(
        RepositoryInterface        $customerOptionRepository,
        FormFactoryInterface       $formFactory,
        string                     $resourceClass,
        string                     $formClass,
        ChannelRepositoryInterface $channelRepository,
    ) {
        // initialize the “resource + form” plumbing
        $this->initialize($customerOptionRepository, $formFactory, $resourceClass, $formClass);

        // initialize your Channel‐hydration
        $this->initializeChannel($channelRepository);
    }

    #[LiveAction]
    public function applyToAll(#[LiveArg] string $valueKey, #[LiveArg] string $translationKey): void
    {
        $value = $this->formValues['values'][$valueKey]['translations'][$translationKey]['value'];

        foreach ($this->formValues['values'][$valueKey]['translations'] as &$translation) {
            $translation['value'] = $value;
        }
    }
}