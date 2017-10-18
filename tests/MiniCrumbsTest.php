<?php

use PHPUnit\Framework\TestCase;
use MiniCrumbs\MiniCrumbs;
use MiniCrumbs\Crumb;

final class MiniCrumbsTest extends TestCase{

    public function testOnlyStringsAsSlugs()
    {
        $m = new MiniCrumbs("standard", "home", ['test' => true]);
        foreach($m->parse() as $crumb){
            $this->assertInstanceOf(Crumb::class, $crumb);
            $this->assertTrue(is_string($crumb->getName()));
            $this->assertTrue(is_string($crumb->getUri()));
        }
    }

    public function testOk()
    {
        $m = new MiniCrumbs("standard", "home", ['test' => true]);
        $this->assertTrue(is_array($m->parse()));
    }

}