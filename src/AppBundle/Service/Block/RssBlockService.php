<?php

namespace Sonata\BlockBundle\Block;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Sonata\BlockBundle\Model\BlockInterface;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;

class RssBlockService extends BaseBlockService
{
    public function __construct($name, EngineInterface $templating)
    {
        parent::__construct($name, $templating);
    }

    /**
     * @return array
     */
    function getDefaultSettings()
    {
        return array(
            'url'     => false,
            'title'   => 'Insert the rss title'
        );
    }

    /**
     * @param FormMapper $formMapper
     * @param BlockInterface $block
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper->add('settings', 'sonata_type_immutable_array', array(
            'keys' => array(
                array('url', 'url', array('required' => false)),
                array('title', 'text', array('required' => false)),
            )
        ));
    }

    /**
     * @param ErrorElement $errorElement
     * @param BlockInterface $block
     */
    function validateBlock(ErrorElement $errorElement, BlockInterface $block)
    {
        $errorElement
            ->with('settings.url')
            ->assertNotNull(array())
            ->assertNotBlank()
            ->end()
            ->with('settings.title')
            ->assertNotNull(array())
            ->assertNotBlank()
            ->assertMaxLength(array('limit' => 50))
            ->end();
    }

    /**
     * @param BlockInterface $block
     * @param Response|null $response
     * @return mixed
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        // merge settings
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        $feeds = false;
        if ($settings['url']) {
            $options = array(
                'http' => array(
                    'user_agent' => 'Sonata/RSS Reader',
                    'timeout' => 2,
                )
            );

            // retrieve contents with a specific stream context to avoid php errors
            $content = @file_get_contents($settings['url'], false, stream_context_create($options));

            if ($content) {
                // generate a simple xml element
                try {
                    $feeds = new \SimpleXMLElement($content);
                    $feeds = $feeds->channel->item;
                } catch(\Exception $e) {
                    // silently fail error
                }
            }
        }

        return $this->renderResponse('SonataBlockBundle:Block:block_core_rss.html.twig', array(
            'feeds'     => $feeds,
            'block'     => $block,
            'settings'  => $settings
        ), $response);
    }
}