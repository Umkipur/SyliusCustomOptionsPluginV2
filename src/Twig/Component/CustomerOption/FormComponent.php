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
use Sylius\Component\Channel\Repository\ChannelRepositoryInterface;
class FormComponent {

    use LiveCollectionTrait;

    /** @use ResourceFormComponentTrait<CustomerOptionInterface> */
    use ResourceFormComponentTrait {
        initialize as public __construct;
    }

    use TemplatePropTrait;

    #[LiveProp(
        hydrateWith: 'hydrateFormValues',
        dehydrateWith: 'dehydrateFormValues',
    )]
    public array $formValues = [];

    public function dehydrateFormValues(): array
    {
        // take $this->formValues (which may contain Channel or Currency entities in
        // nested price->channel->baseCurrency) and turn every object into a scalar.
        $data = $this->formValues;

        foreach ($data['values'] as &$value) {
            foreach ($value['prices'] as &$price) {
                // the channel itself is an entity; replace it with just its code:
                /** @var ChannelInterface $chan */
                $chan = $price['channel'];

                $price['channel'] = [
                    'code' => $chan['code'],
                    'name' => $chan['name'],
                ];
            }
        }

        return $data;
    }

    public function hydrateFormValues(array $raw): array
    {
        // we don’t need to turn those arrays back into Channel objects here,
        // because when you eventually persist you’ll do that manually.  So
        // just return the scalar array exactly as it came in:
        return $raw;
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
