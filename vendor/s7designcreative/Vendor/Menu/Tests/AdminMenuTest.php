<?php

namespace S7D\Vendor\Menu\Tests;


use S7D\Core\Helpers\Container;
use S7D\Core\Helpers\TestHelper;
use S7D\Core\HTTP\Response;
use S7D\Vendor\Menu\Controller\AdminMenuController;

class AdminMenuTest extends TestHelper
{
    /**
     * @var AdminMenuController
     */
    private $controller;

    public function setUp()
    {
        $this->controller = new AdminMenuController($this->mockContainer());
    }

    public function testIndex()
    {
        $this->assertContains(
            '<td>Main menu</td>',
            $this->controller->index()->getOutput(),
            'Expected that main menu name exists, but there is no such data'
        );
        $this->assertContains(
            '<td>1</td>',
            $this->controller->index()->getOutput(),
            'Expected that main menu id exists, but there is no such data'
        );
    }

    public function testCreate()
    {
        $this->assertContains(
            '<h4>Add new menu</h4>',
            $this->controller->create()->getOutput(),
            'Expected form title for creating new menu but there is no such element'
        );
        $this->assertContains(
            '<input name="menu_name" type="text" placeholder="Menu name" id="menu_name" required/>',
            $this->controller->create()->getOutput(),
            'Expected form input for creating new menu but there is no such element'
        );
    }

    public function testShow()
    {
        $this->assertContains(
            '<input name="menu_name" type="text" placeholder="Menu name" value="Main menu" id="menu_name" required/>',
            $this->controller->show(1)->getOutput(),
            'Expected that single view has Menu to edit but there is no such field'
        );
    }

    public function testEdit()
    {
        $_SERVER['HTTP_REFERER'] = 'http://localhost';
        $this->assertEquals(
            true,
            $this->controller->edit(1) instanceof Response,
            'Expected redirect but there is no redirect'
        );
    }

    public function testSave()
    {
        $_SERVER['HTTP_REFERER'] = 'http://localhost';
        $this->assertEquals(
            true,
            $this->controller->save(1) instanceof Response,
            'Expected redirect but there is no redirect'
        );
    }

    public function testDelete()
    {
        $_SERVER['HTTP_REFERER'] = 'http://localhost';
        $this->assertEquals(
            true,
            $this->controller->delete(1) instanceof Response,
            'Expected redirect but there is no redirect for delete method'
        );
    }

    protected function mockEntityManager()
    {

        $em = $this->getMockBuilder('Doctrine\ORM\EntityManager\EntityManager')
                   ->setMethods(array('getRepository', 'find', 'persist', 'flush', 'findAll', 'remove'))
                   ->getMock();
        $em->method('getRepository')->will($this->returnSelf());
        $em->method('find')->will($this->returnValue($this->mockMenuEntity()));
        $em->method('findAll')->will($this->returnValue(array(0 => $this->mockMenuEntity())));

        return $em;
    }

    protected function mockMenuEntity()
    {

        $menu = $this->getMockBuilder('\S7D\Vendor\Menu\Entity\Menu')
                     ->setMethods(array('getName', 'getId'))
                     ->getMock();
        $menu->method('getName')->will($this->returnValue('Main menu'));
        $menu->method('getId')->will($this->returnValue(1));


        return $menu;
    }

    /**
     * {@inheritdoc}
     *
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function mockRequest()
    {

        $request = $this->getMockBuilder('\S7D\Core\HTTP\Request')
                        ->setMethods(array('isPost', 'getAll'))
                        ->getMock();

        $request->method('isPost')->will($this->returnValue(false));
        $request->method('getAll')->will($this->returnValue(array('menu_name' => 'Main menu')));

        return $request;
    }


}