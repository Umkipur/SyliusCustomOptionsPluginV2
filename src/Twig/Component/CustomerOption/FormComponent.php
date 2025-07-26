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

class FormComponent {
    use LiveCollectionTrait;

    /** @use ResourceFormComponentTrait<CustomerOptionInterface> */
    use ResourceFormComponentTrait {
        initialize as public __construct;
    }

    use TemplatePropTrait;

    #[LiveProp(useSerializerForHydration: true)]
    public array $formValues = [];

    #[LiveAction]
    public function applyToAll(#[LiveArg] string $valueKey, #[LiveArg] string $translationKey): void
    {
        $value = $this->formValues['values'][$valueKey]['translations'][$translationKey]['value'];

        foreach ($this->formValues['values'][$valueKey]['translations'] as &$translation) {
            $translation['value'] = $value;
        }
    }

    public function getFormName(): string
    {
        return 'brille24_customer_option';
    }
}