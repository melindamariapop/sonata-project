<?php

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\PageBundle\Admin;

use Knp\Menu\ItemInterface as MenuItemInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\Cache\CacheManagerInterface;
use Sonata\PageBundle\Exception\InternalErrorException;
use Sonata\PageBundle\Exception\PageNotFoundException;
use Sonata\PageBundle\Model\PageInterface;
use Sonata\PageBundle\Model\PageManagerInterface;
use Sonata\PageBundle\Model\SiteInterface;
use Sonata\PageBundle\Model\SiteManagerInterface;

/**
 * Admin definition for the Page class.
 *
 * @author Thomas Rabaix <thomas.rabaix@sonata-project.org>
 */
class PageAdmin extends \Sonata\PageBundle\Admin\PageAdmin
{

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        // define group zoning
        $formMapper
            ->with('form_page.group_main_label', array('class' => 'col-md-6'))->end()
            ->with('form_page.group_seo_label', array('class' => 'col-md-6'))->end()
            ->with('form_page.group_advanced_label', array('class' => 'col-md-6'))->end()
        ;

        if (!$this->getSubject() || (!$this->getSubject()->isInternal() && !$this->getSubject()->isError())) {
            $formMapper
                ->with('form_page.group_main_label')
                ->add('url', 'text', array('attr' => array('readonly' => 'readonly')))
                ->end()
            ;
        }

        if ($this->hasSubject() && !$this->getSubject()->getId()) {
            $formMapper
                ->with('form_page.group_main_label')
                ->add('site', null, array('required' => true, 'read_only' => true))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_main_label')
            ->add('name')
            ->add('enabled', null, array('required' => false))
            ->add('showOnMenu', null, ['required' => false, 'label' => 'Show on menu'])
            ->add('position')
            ->end()
        ;

        if ($this->hasSubject() && !$this->getSubject()->isInternal()) {
            $formMapper
                ->with('form_page.group_main_label')
                ->add('type', 'sonata_page_type_choice', array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_main_label')
            ->add('templateCode', 'sonata_page_template', array('required' => true))
            ->end()
        ;

        if (!$this->getSubject() || ($this->getSubject() && $this->getSubject()->getParent()) || ($this->getSubject() && !$this->getSubject()->getId())) {
            $formMapper
                ->with('form_page.group_main_label')
                ->add('parent', 'sonata_page_selector', array(
                    'page' => $this->getSubject() ?: null,
                    'site' => $this->getSubject() ? $this->getSubject()->getSite() : null,
                    'model_manager' => $this->getModelManager(),
                    'class' => $this->getClass(),
                    'required' => false,
                    'filter_choice' => array('hierarchy' => 'root'),
                ), array(
                    'admin_code' => $this->getCode(),
                    'link_parameters' => array(
                        'siteId' => $this->getSubject() ? $this->getSubject()->getSite()->getId() : null,
                    ),
                ))
                ->end()
            ;
        }

        if (!$this->getSubject() || !$this->getSubject()->isDynamic()) {
            $formMapper
                ->with('form_page.group_main_label')
                ->add('pageAlias', null, array('required' => false))
                ->add('target', 'sonata_page_selector', array(
                    'page' => $this->getSubject() ?: null,
                    'site' => $this->getSubject() ? $this->getSubject()->getSite() : null,
                    'model_manager' => $this->getModelManager(),
                    'class' => $this->getClass(),
                    'filter_choice' => array('request_method' => 'all'),
                    'required' => false,
                ), array(
                    'admin_code' => $this->getCode(),
                    'link_parameters' => array(
                        'siteId' => $this->getSubject() ? $this->getSubject()->getSite()->getId() : null,
                    ),
                ))
                ->end()
            ;
        }

        if (!$this->getSubject() || !$this->getSubject()->isHybrid()) {
            $formMapper
                ->with('form_page.group_seo_label')
                ->add('slug', 'text', array('required' => false))
                ->add('customUrl', 'text', array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_seo_label', array('collapsed' => true))
            ->add('title', null, array('required' => false))
            ->add('metaKeyword', 'textarea', array('required' => false))
            ->add('metaDescription', 'textarea', array('required' => false))
            ->end()
        ;

        if ($this->hasSubject() && !$this->getSubject()->isCms()) {
            $formMapper
                ->with('form_page.group_advanced_label', array('collapsed' => true))
                ->add('decorate', null, array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with('form_page.group_advanced_label', array('collapsed' => true))
            ->add('javascript', null, array('required' => false))
            ->add('stylesheet', null, array('required' => false))
            ->add('rawHeaders', null, array('required' => false))
            ->end()
        ;

        $formMapper->setHelps(array(
            'name' => 'help_page_name',
        ));
    }
}
