<?php

namespace AppBundle\Menu;

use Knp\Menu\FactoryInterface;

class MenuBuilder
{
    private $factory;

    /**
     * @param FactoryInterface $factory
     *
     * Add any other dependency you need
     */
    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('main');

        $menu->addChild('Home', array('route' => 'homepage'));
        $menu['Home']->setLabel('Home');
        $menu->addChild('Login', array('route' => 'login'));
        $menu->addChild('Google', array('uri' => 'http://www.google.com'));

        return $menu;
    }

    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('sidebar');

        $menu->addChild('Google', array('uri' => 'http://www.google.com'));
        $menu->addChild('Facebook', array('uri' => 'http://www.facebook.com'));

        // ... add more children

        return $menu;
    }
}