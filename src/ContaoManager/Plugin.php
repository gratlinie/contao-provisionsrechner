<?php

declare(strict_types=1);

namespace Gratlinie\ProvisionsrechnerBundle\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Gratlinie\ProvisionsrechnerBundle\GratlinieProvisionsrechnerBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(GratlinieProvisionsrechnerBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
        ];
    }
}
