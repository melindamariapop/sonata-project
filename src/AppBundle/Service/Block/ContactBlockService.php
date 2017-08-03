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

class ContactBlockService extends BaseBlockService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * ContactBlockService constructor.
     * @param string $name
     * @param EngineInterface $templating
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
        return "Contact";
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'   => 'CONTACT BUSINESS CASUAL',
            'phone'   => '4584848548',
            'mobilePhone'   => '4584848548',
            'email' => 'sonata@training.test',
            'address' => 'Sonata Training test 543532, cluj',
            'mapUrl' => "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d87426.01400903262!2d23.546301463343934!3d46.7833641822963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47490c1f916c0b8b%3A0xbbc601c331f148b!2sCluj-Napoca!5e0!3m2!1sen!2sro!4v1493365792776",
            'template' => 'AppBundle:Blocks:contact.html.twig',
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
                                'label' => 'Title',
                                'data' => "CONTACT BUSINESS CASUAL"
                            ]],
                            ['address', 'sonata_simple_formatter_type', [
                                'format' => 'richhtml',
                                'required' => true,
                                'label' => 'Address'
                            ]]
                        ]
                    ]
                ]
            ])
            ->add('settings', 'sonata_type_immutable_array', [
                'keys' => [
                    ['phone', 'text', [
                        'label' => 'Phone',
                    ]],
                    ['mobilePhone', 'text', [
                        'required' => true,
                        'label' => 'Mobile Phone',
                    ]],
                    ['mapUrl', 'text', [
                        'required' => true,
                        'label' => 'Url map'
                    ]]
                ]]
            )
            ;
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