<?php
namespace AppBundle\Service\Block;

use AppBundle\Entity\Product;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Application\Sonata\PageBundle\Entity\Block;
use Application\Sonata\PageBundle\Entity\BlockTranslation;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Sonata\CoreBundle\Model\Metadata;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GalleryBlockService extends BaseBlockService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * GalleryBlockService constructor.
     * @param null|string $name
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
        return "Gallery";
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'   => 'The Gallery title :)',
            'description' => "Awesome gallery",
            'nrPhotos' => 8,
            'gallery' => $this->em->getRepository(Gallery::class)->findBy([],['id'=> 'asc']),
            'template' => 'AppBundle:Blocks:gallery.html.twig',
        ));
    }

    /**
     * @param FormMapper $formMapper
     * @param BlockInterface $block
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->add('settings', 'sonata_type_immutable_array', [
                'keys' => [
                    ['title', 'text', [
                        'required' => true,
                        'label' => 'Gallery Title'
                    ]],
                    ['nrPhotos', 'integer', [
                        'required' => true,
                        'label' => 'Number of photos on the page'
                    ]]
                ],
            ])
            ->add('gallery', 'entity',[
                'class' => Gallery::class,
                'required' => true,
                'label' => 'Choose a gallery'
            ]);
        $formMapper
            ->add('translations', 'a2lix_translations', [
                'label' => 'Translatable fields ',
                'fields' => [
                    'translatableFields' => [
                        'label' => false,
                        'field_type' => 'sonata_type_immutable_array',
                        'keys' => [
                            ['description', 'sonata_simple_formatter_type', [
                                'format' => 'richhtml',
                                'required' => true,
                                'label' => 'Gallery Description'
                            ]]
                        ]
                    ]
                ]
            ]);
    }

    /**
     * @param BlockContextInterface $blockContext
     * @param Response|null $response
     * @return Response
     */
    public function execute(BlockContextInterface $blockContext, Response $response = null)
    {
        $products = $this->em->getRepository(Product::class)->findAll();
        $block = $this->em->getRepository(Block::class)->find($blockContext->getBlock());

        return $this->renderResponse($blockContext->getTemplate(), array(
            'products' => $products,
            'context' => $blockContext,
            'block' => $block,
            'settings' => $blockContext->getSettings(),
            'gallery' => $block->getGallery(),
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockMetadata($code = null)
    {
        return new Metadata($this->getName(), (!is_null($code) ? $code : $this->getName()), false, 'SonataBlockBundle', [
            'class' => ' fa fa-file-text',
        ]);
    }
}