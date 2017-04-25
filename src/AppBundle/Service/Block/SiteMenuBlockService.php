<?php

namespace AppBundle\Service\Block;

use Knp\Menu\Provider\MenuProviderInterface;
use Sonata\BlockBundle\Block\Service\MenuBlockService;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SiteMenuBlockService extends MenuBlockService
{
    public function __construct($name, EngineInterface $templating, MenuProviderInterface $menuProvider, $mainMenu, $sidebarMenu)
    {
        parent::__construct($name, $templating, $menuProvider, [$mainMenu => $mainMenu, $sidebarMenu => $sidebarMenu]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title' => $this->getName(),
            'cache_policy' => 'public',
            'template' => 'SonataBlockBundle:Block:block_core_menu.html.twig',
            'menu_name' => '',
            'safe_labels' => false,
            'current_class' => 'active',
            'first_class' => false,
            'last_class' => false,
            'current_uri' => null,
            'menu_class' => 'list-group',
            'children_class' => 'list-group-item'
        ));
    }
    /**
     * @return array
     */
    protected function getFormSettingsKeys()
    {
        return [
            ['title', 'text', ['required' => false]],
            ['cache_policy', 'choice', ['choices' => ['public', 'private']]],
            ['menu_name', 'choice', ['choices' => $this->menus, 'required' => false]],
            ['template', 'choice', [
                'required' => true,
                'choices' => [
                    'SonataBlockBundle:Block:block_core_menu.html.twig' => 'Default',
                    '::menu.html.twig' => 'Custom template'
                ]
            ]],
            ['safe_labels', 'checkbox', ['required' => false]],
            ['current_class', 'text', ['required' => false]],
            ['first_class', 'text', ['required' => false]],
            ['last_class', 'text', ['required' => false]],
            ['menu_class', 'text', ['required' => false]],
            ['children_class', 'text', ['required' => false]],
        ];
    }


}