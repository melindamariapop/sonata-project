<?php
namespace AppBundle\Service\Block;

use AppBundle\Entity\Faq;
use Application\Sonata\PageBundle\Entity\Block;
use Doctrine\ORM\EntityManager;
use Sonata\BlockBundle\Block\BaseBlockService;
use Sonata\BlockBundle\Block\BlockContextInterface;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqBlockService extends BaseBlockService
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * MainContentBlockService constructor.
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
        return "Faqs";
    }

    /**
     * {@inheritdoc}
     */
    public function configureSettings(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'title'   => 'FAQS',
            'description'   => 'Frequently asked questions ',
            'template' => 'AppBundle:Blocks:faq.html.twig',
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
                        'label' => 'Title',
                    ]],
                    ['description', 'text', [
                        'required' => true,
                        'label' => 'Description',
                    ]],
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
            'faqs' => $this->em->getRepository(Faq::class)->findBy(['showInPage' => true])
        ), $response);
    }
}