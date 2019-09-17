<?php

/*
 * This file is part of [package name].
 *
 * (c) John Doe
 *
 * @license LGPL-3.0-or-later
 */

namespace Eknoes\ContaoPublicationsCrossrefFetcher\Tests;

use Eknoes\ContaoPublicationsCrossrefFetcher\ContaoPublicationsCrossrefFetcherBundle;
use PHPUnit\Framework\TestCase;

class ContaoPublicationsCrossrefFetcherBundleTest extends TestCase
{
    public function testCanBeInstantiated()
    {
        $bundle = new ContaoPublicationsCrossrefFetcherBundle();

        $this->assertInstanceOf('Eknoes\ContaoPublicationsCrossrefFetcher\ContaoPublicationsCrossrefFetcherBundle', $bundle);
    }
}
