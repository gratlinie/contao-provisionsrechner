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
        $template->set('orders', min(400, max(10, (int) $model->prOrders ?: 60)));
        $template->set('average', min(80, max(10, (int) $model->prAverage ?: 25)));
        $template->set('rate', min(35, max(5, (float) $model->prRate ?: 14)));
        $template->set('years', min(10, max(2, (int) $model->prYears ?: 3)));

        return $template->getResponse();
    }
}
