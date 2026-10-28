<?php

declare(strict_types=1);

namespace Gratlinie\ProvisionsrechnerBundle\Controller;

use Contao\CoreBundle\Controller\FrontendModule\AbstractFrontendModuleController;
use Contao\CoreBundle\DependencyInjection\Attribute\AsFrontendModule;
use Contao\CoreBundle\Twig\FragmentTemplate;
use Contao\ModuleModel;
use Contao\StringUtil;
use Gratlinie\ProvisionsrechnerBundle\Event\OffersEvent;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;

/**
 * Compares the commission of an ordering platform with the costs of an own shop.
 */
#[AsFrontendModule(type: 'provisionsrechner', category: 'miscellaneous', template: 'frontend_module/provisionsrechner')]
class ProvisionsrechnerController extends AbstractFrontendModuleController
{
    /**
     * Default start, minimum, maximum and step of each slider.
     */
    public const SLIDERS = [
        'orders' => ['60', '10', '400', '5'],
        'average' => ['25', '10', '80', '1'],
        'rate' => ['14', '5', '35', '0.5'],
    ];

    public function __construct(private readonly EventDispatcherInterface $eventDispatcher)
    {
    }

    protected function getResponse(FragmentTemplate $template, ModuleModel $model, Request $request): Response
    {
        $offers = [];

        foreach (StringUtil::deserialize($model->prOffers, true) as $index => $row) {
            if (!\is_array($row) || '' === trim((string) ($row['title'] ?? ''))) {
                continue;
            }

            $offers[] = [
                'code' => 'offer'.$index,
                'title' => trim((string) $row['title']),
                'yearly' => (float) str_replace(',', '.', (string) ($row['yearly'] ?? '0')),
                'once' => (float) str_replace(',', '.', (string) ($row['once'] ?? '0')),
            ];
        }

        $offers = $this->eventDispatcher->dispatch(new OffersEvent($model, $offers))->getOffers();

        $template->set('offers', $offers);
        $template->set('name', $model->prName ?: 'Eigener Shop');
        $template->set('currency', strtoupper((string) $model->prCurrency) ?: 'EUR');
        $template->set('years', min(20, max(1, (int) $model->prYears ?: 3)));
        $template->set('sliders', [
            'orders' => $this->slider('orders', $model->prOrders, $model->prOrdersLabel),
            'average' => $this->slider('average', $model->prAverage, $model->prAverageLabel),
            'rate' => $this->slider('rate', $model->prRate, $model->prRateLabel),
        ]);

        return $template->getResponse();
    }

    /**
     * @return array{label: string|null, value: float, min: float, max: float, step: float}
     */
    private function slider(string $name, mixed $config, string|null $label): array
    {
        $values = StringUtil::deserialize($config, true);
        $number = static fn (int $index): float => (float) str_replace(',', '.', (string) (($values[$index] ?? '') !== '' ? $values[$index] : self::SLIDERS[$name][$index]));

        [$min, $max, $step] = [$number(1), $number(2), $number(3)];

        if ($max <= $min || $step <= 0) {
            [$min, $max, $step] = array_map('floatval', \array_slice(self::SLIDERS[$name], 1));
        }

        return [
            'label' => trim((string) $label) ?: null,
            'value' => min($max, max($min, $number(0))),
            'min' => $min,
            'max' => $max,
            'step' => $step,
        ];
    }
}
