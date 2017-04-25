<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;


class RouteListener
{
    /**
     * @var string
     */
    private $defaultLocale;

    public function __construct($defaultLocale)
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($event->isMasterRequest()) {
            if ($request->getSession()->has('_locale')){
                $request->setLocale($request->getSession()->get('_locale'));
            } else {
                $request->setLocale($this->defaultLocale);
            }
        }
    }
}