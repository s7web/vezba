<?php

namespace S7D\Vendor\Menu\Controller;

use S7D\Core\Routing\Controller;
use S7D\Core\Validator\Validator;
use S7D\Vendor\Menu\Entity\Menu;

/**
 * Class AdminMenuController
 * @package S7D\Vendor\Menu\Controller
 */
class AdminMenuController extends Controller
{

    /**
     * List all menus and render them
     *
     * @return \S7D\Core\HTTP\Response
     */
    public function index()
    {

        $menus = $this->em->getRepository('S7D\Vendor\Menu\Entity\Menu')->findAll();

        return $this->render(array('menus' => $menus));
    }

    /**
     * Show specific menu
     *
     * @param int $id Id of menu
     *
     * @return \S7D\Core\HTTP\Response
     */
    public function show($id)
    {

        $menu = $this->em->getRepository('S7D\Vendor\Menu\Entity\Menu')->find($id);

        if ( ! $menu) {
            return $this->redirectBack();
        }

        return $this->render(array('menu' => $menu));
    }

    /**
     * Display form for creating new menu
     *
     * @return \S7D\Core\HTTP\Response
     */
    public function create()
    {
        return $this->render();
    }

    /**
     * Show form with binded data for editing menu
     *
     * @param int $id Id of menu
     *
     * @return \S7D\Core\HTTP\Response
     */
    public function edit($id)
    {
        $data = $this->request->getAll();
        $v    = Validator::make($data, array('menu_name' => 'min:3|required'));
        if ( ! $v->isValid()) {
            $this->session->setFlash('Data is not valid, please look at examples and follow validation instructions');

            return $this->redirectBack();
        }
        /** @var Menu $menu */
        $menu = $this->em->getRepository('S7D\Vendor\Menu\Entity\Menu')->find($id);
        if ( ! $menu) {
            $this->session->setFlash('Error, there is no such menu');

            return $this->redirectBack();
        }
        $menu->setName($data['menu_name']);
        $this->em->persist($menu);
        $this->em->flush();
        $this->session->setFlash('Successfully updated menu');

        return $this->redirectBack();
    }

    /**
     * Save new menu to database
     *
     * @return \S7D\Core\HTTP\Response
     */
    public function save()
    {

        $data = $this->request->getAll();
        $v = Validator::make($data, array('menu_name' => 'min:3|required'));
        if(!$v->isValid()){
            $this->session->setFlash('Data is not valid please follow instructions and examples');
            return $this->redirectBack();
        }
        $menu = new Menu();
        $menu->setName($data['menu_name']);
        $this->em->persist($menu);
        $this->em->flush();
        return $this->redirect('/admin/menu/list');
    }

    /**
     * Delete menu by id
     *
     * @param int $id id of menu
     *
     * @return \S7D\Core\HTTP\Response
     */
    public function delete($id)
    {
        $menu = $this->em->getRepository('S7D\Vendor\Menu\Entity\Menu')->find($id);
        if(!$menu){
            $this->session->setFlash('There is no such menu');
            return $this->redirectBack();
        }

        $this->em->remove($menu);
        $this->em->flush();
        return $this->redirectBack();
    }
}