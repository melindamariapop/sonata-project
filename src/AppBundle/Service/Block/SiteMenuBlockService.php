<?php

namespace AppBundle\Service\Block;

use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\Service\MenuBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class SiteMenuBlockService extends MenuBlockService
{
    public function __construct($name, EngineInterface $templating, MenuProviderInterface $menuProvider, $mainMenu, $sidebarMenu)
    {
        parent::__construct($name, $templating, $menuProvider, [$mainMenu => $mainMenu, $sidebarMenu => $sidebarMenu]);
    }
}