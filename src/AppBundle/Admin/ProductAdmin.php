<?php

namespace AppBundle\Admin;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;

class ProductAdmin extends AbstractAdmin
{
    /**
     * @param mixed $object
     * @return string
     */
    public function toString($object)
    {
        return $object instanceof Product
            ? $object->getTitle()
            : 'Product'; // shown in the breadcrumb on the create view
    }

    /**
     * @param DatagridMapper $datagridMapper
     */
    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('description')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('category', null, array(), 'entity', [
                'class'    => 'AppBundle\Entity\Category',
                'property' => 'name', // In Symfony2: 'property' => 'name'
            ])
        ;
    }

    /**
     * @param ListMapper $listMapper
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('description')
            ->add('category.name')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('_action', null, array(
                'actions' => array(
                    'show' => array(),
                    'edit' => array(),
                    'delete' => array(),
                )
            ))
        ;
    }

    /**
     * @param FormMapper $formMapper
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('description', 'sonata_formatter_type',[
                'event_dispatcher' => $formMapper->getFormBuilder()->getEventDispatcher(),
                'format_field' => 'descriptionFormatter',
                'source_field' => 'rawDescription',
                'source_field_options'      => [
                    'attr' => ['class' => 'span10', 'rows' => 10]
                ],
                'listener'       => true,
                'target_field'   => 'description',
                'ckeditor_context'     => 'default',
            ])
            ->add('category', 'sonata_type_model', [
                'class' => Category::class,
                'property' => 'name',
            ])
            ->add('gallery', 'sonata_type_model_list',
                [
                    'required' => false
                ],
                [
                    'link_parameters'   => [
                        'context' => 'product'
                    ]
                ])
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }

    /**
     * @param ShowMapper $showMapper
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('title')
            ->add('description')
            ->add('createdAt')
            ->add('updatedAt')
        ;
    }
}
