<?php

namespace Tests\AppBundle\Entity;

use AppBundle\Entity\Link;

class LinkTest extends \PHPUnit_Framework_TestCase
{
    public function testGetUrl()
    {
        $expectedURl = 'ftp://google.com';
        $link = new Link();
        $link->setUrl($expectedURl);
        $this->assertEquals($expectedURl, $link->getUrl(), "Link URL doesn't match");
    }
}
