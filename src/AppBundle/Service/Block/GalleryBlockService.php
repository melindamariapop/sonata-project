<?php
namespace AppBundle\Service\Block;

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

    public function __construct($name, EngineInterface $templating)
    {
        parent::__construct($name, $templating);
    }

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
            'template' => 'AppBundle::gallery.html.twig',
        ));
    }

    /**
     * @param FormMapper $formMapper
     * @param BlockInterface $block
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', [
            'keys' => [
                ['title', 'text', [
                    'required' => true,
                    'label' => 'Gallery Title'
                ]],
                ['description', 'text', [
                    'required' => true,
                    'label' => 'Gallery Description'
                ]],
                ['nrPhotos', 'integer', [
                    'required' => true,
                    'label' => 'Number of photos on the page'
                ]]
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
        $photos = [
            '/bundles/app/images/cover.jpg',
            '/bundles/app/images/download.jpg',
            '/bundles/app/images/images.jpg',
            '/bundles/app/images/img2.png',
            '/bundles/app/images/img111.jpg',
            '/bundles/app/images/pic.jpg',
            '/bundles/app/images/wood1.jpg',
        ];

        return $this->renderResponse($blockContext->getTemplate(), array(
            'photos'     => $photos,
            'context' => $blockContext,
            'block' => $blockContext->getBlock(),
            'settings' => $blockContext->getSettings(),
        ), $response);
    }
}