<?php

declare(strict_types=1);

namespace Gratlinie\ProvisionsrechnerBundle\Event;

use Contao\ModuleModel;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Lets other bundles provide or change the offers the commission is compared
 * with, e.g. from a shop's product table. Each offer is an array with the keys
 * code, title, yearly (running costs per year) and once (one-off costs).
 */
final class OffersEvent extends Event
{
    /**
     * @param list<array{code: string, title: string, yearly: float, once: float}> $offers
     */
    public function __construct(
        private readonly ModuleModel $model,
        private array $offers,
    ) {
    }

    public function getModel(): ModuleModel
    {
        return $this->model;
    }

    /**
     * @return list<array{code: string, title: string, yearly: float, once: float}>
     */
    public function getOffers(): array
    {
        return $this->offers;
    }

    /**
     * @param list<array{code: string, title: string, yearly: float, once: float}> $offers
     */
    public function setOffers(array $offers): void
    {
        $this->offers = array_values($offers);
    }
}
