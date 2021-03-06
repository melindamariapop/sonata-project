<?php

namespace AppBundle\Menu;

use Application\Sonata\PageBundle\Entity\Page;
use Application\Sonata\PageBundle\Entity\Site;
use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Sonata\PageBundle\CmsManager\CmsPageManager;
use Symfony\Component\HttpFoundation\RequestStack;

class MenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    /**
     * @var CmsPageManager
     */
    private $pageManager;

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var RequestStack
     */
    private $requestStack;

    /**
     * MenuBuilder constructor.
     * @param FactoryInterface $factory
     * @param CmsPageManager $pageManager
     * @param EntityManager $em
     * @param RequestStack $requestStack
     */
    public function __construct(FactoryInterface $factory, CmsPageManager $pageManager, EntityManager $em, RequestStack $requestStack)
    {
        $this->factory = $factory;
        $this->pageManager = $pageManager;
        $this->em = $em;
        $this->requestStack = $requestStack;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createMainMenu(array $options)
    {
        $menu = $this->factory->createItem('main');
        if (array_key_exists('menu_class', $options)) {
            $menu->setChildrenAttribute('class', $options['menu_class']);
        }

        $pages = $this->getPagesForMainMenu();
        foreach ($pages as $page) {
            $menu->addChild($page['name'], ['uri' => $page['url']]);
        }

        return $menu;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    public function createSidebarMenu(array $options)
    {
        $menu = $this->factory->createItem('sidebar');
        $menu->setAttribute('class', $options['menu_class']);
        $menu->setChildrenAttribute('class', $options['children_class']);

        $menu->addChild('Google', array('uri' => 'http://www.google.com'));
        $menu->addChild('Facebook', array('uri' => 'http://www.facebook.com'));

        // ... add more children

        return $menu;
    }

    /**
     * @return array|bool
     */
    public function getPagesForMainMenu()
    {
        $page = $this->pageManager->getCurrentPage();
        $homepage = $page && $page->getParent() ? $page->getParent() : $this->em->getRepository(Page::class)->findOneBy(['site' => $this->getCurrentSite(), 'url' => '/']);

        $pages = $this->em->getRepository('ApplicationSonataPageBundle:Page')
            ->createQueryBuilder('p')
            ->select('p.name, p.url')
            ->where('p.site = :site')
            ->andWhere('p.parent = :homepage or p.parent is null')
            ->andWhere('p.showOnMenu = true')
            ->setParameters([
                'site' => $this->getCurrentSite(),
                'homepage' => $homepage
            ])
            ->orderBy('p.position')
            ->getQuery()
            ->getResult()
            ;


        return $pages;
    }

    public function getCurrentSite()
    {
        $page = $this->pageManager->getCurrentPage();
        if ($page) {
            return $page->getSite();
        }

        return $this->em->getRepository(Site::class)->findOneBy(['host'=>$this->requestStack->getCurrentRequest()->getHost()]);
    }
}