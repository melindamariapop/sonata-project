<?php
namespace AppBundle\Service\Block;

use AppBundle\Entity\Product;
use Application\Sonata\PageBundle\Entity\Block;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoriesContentBlockService extends BaseBlockService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MainContentBlockService constructor.
     * @param string $name
     * @param EngineInterface $templating
     * @param EntityManager $em
     */
    public function __construct($name, EngineInterface $templating, EntityManager $em)
    {
        $this->em = $em;
        parent::__construct($name, $templating);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return "Categories Content";
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'   => 'Title',
            'description' => "Description",
            'categoriesList' => [],
            'template' => 'AppBundle:Blocks:productsContent.html.twig',
        ));
    }

    /**
     * @param FormMapper $formMapper
     * @param BlockInterface $block
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->add('translations', 'a2lix_translations', [
                'label' => 'Translatable fields ',
                'fields' => [
                    'translatableFields' => [
                        'label' => false,
                        'field_type' => 'sonata_type_immutable_array',
                        'keys' => [
                            ['title', 'text', [
                                'required' => true,
                                'label' => 'Title'
                            ]],
                            ['description', 'sonata_simple_formatter_type', [
                                'format' => 'richhtml',
                                'required' => true,
                                'label' => 'Gallery Description'
                            ]],
                        ]
                    ]
                ]
            ]);
        if($block->getId() != null ){
            $formMapper->add('categoriesList', 'sonata_type_collection', [
                'by_reference'       => false,
                'label'              => 'Categories List',
            ], [
                    'edit'     => 'inline',
                    'inline'   => 'table',
                    'sortable' => 'position',
                ]
            );
        }

    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null $response
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $block = $this->em->getRepository(Block::class)->find($blockContext->getBlock());
//        var_dump($block->getCategoriesList());die;
        return $this->renderResponse($blockContext->getTemplate(), array(
            'context' => $blockContext,
            'block' => $block,
            'settings' => $blockContext->getSettings(),
            'categories' => $block->getCategoriesList()
        ), $response);
    }
}