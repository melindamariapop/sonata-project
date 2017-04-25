<?php
namespace AppBundle\Service\Block;

use Application\Sonata\PageBundle\Entity\Block;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MainContentBlockService extends BaseBlockService
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
        return "Main Content";
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'   => 'Title',
            'shortDescription' => "Short Description",
            'description' => "Description",
            'template' => 'AppBundle:Blocks:mainContent.html.twig',
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
                            ['shortDescription', 'text', [
                                'required' => true,
                                'label' => 'Short Description'
                            ]],
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
        $block = $this->em->getRepository(Block::class)->find($blockContext->getBlock());

        return $this->renderResponse($blockContext->getTemplate(), array(
            'context' => $blockContext,
            'block' => $block,
            'settings' => $blockContext->getSettings(),
        ), $response);
    }
}