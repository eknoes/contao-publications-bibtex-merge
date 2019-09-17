<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Eknoes\ContaoPublicationsCrossrefFetcher\ContaoManager;

use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Eknoes\ContaoPublications\ContaoPublicationsBundle;
use Eknoes\ContaoPublicationsCrossrefFetcher\ContaoPublicationsCrossrefFetcherBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create(ContaoPublicationsCrossrefFetcherBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class, ContaoPublicationsBundle::class]),
        ];
    }

}
