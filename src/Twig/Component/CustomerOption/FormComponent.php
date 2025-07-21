<?php
declare(strict_types=1);

namespace Brille24\SyliusCustomerOptionsPlugin\Twig\Component\CustomerOption;

use Sylius\Bundle\UiBundle\Twig\Component\LiveCollectionTrait;
use Sylius\Bundle\UiBundle\Twig\Component\ResourceFormComponentTrait;
use Sylius\Bundle\UiBundle\Twig\Component\TemplatePropTrait;
use Sylius\Component\Product\Model\ProductOptionInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveArg;

#[AsLiveComponent]
class FormComponent {
    use LiveCollectionTrait;


    use ResourceFormComponentTrait {
        initialize as public __construct;
    }

    use TemplatePropTrait;

    #[LiveAction]
    public function applyToAll(#[LiveArg] string $valueKey, #[LiveArg] string $translationKey): void
    {
        $value = $this->formValues['values'][$valueKey]['translations'][$translationKey]['value'];

        foreach ($this->formValues['values'][$valueKey]['translations'] as &$translation) {
            $translation['value'] = $value;
        }
    }
}