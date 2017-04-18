<?php
namespace AppBundle\Service\Block;

use AppBundle\Entity\Product;
use Application\Sonata\MediaBundle\Entity\Gallery;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
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
            'template' => 'AppBundle::gallery.html.twig',
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
                    ['description', 'sonata_simple_formatter_type', [
                        'format' => 'richhtml',
                        'required' => true,
                        'label' => 'Gallery Description'
                    ]],
                    ['nrPhotos', 'integer', [
                        'required' => true,
                        'label' => 'Number of photos on the page'
                    ]],
                    ['gallery', 'entity', [
                        'class' => Gallery::class,
                        'required' => true,
                        'label' => 'gallery test'
                    ]]
                ],
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

        return $this->renderResponse($blockContext->getTemplate(), array(
            'products' => $products,
            'context' => $blockContext,
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ), $response);
    }
}