<?php

namespace S7D\Vendor\Menu\Tests;


use S7D\Core\Helpers\Container;
use S7D\Core\Helpers\TestHelper;
use S7D\Vendor\Menu\Controller\AdminMenuController;

class AdminMenuTest extends TestHelper
{

    public function testIndex(){

        $testController = new AdminMenuController($this->mockContainer());
        $this->assertContains('<h4>Menus list</h4>', $testController->index()->getOutput());
    }

    public function testShow(){

    }

    public function testEdit(){

    }

    public function testSave(){

    }

    public function testDelete(){

    }
}