<?php

namespace AppBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{

    /**
     * @var array
     */
    private $preferredLanguage;

    /**
     * @param array $preferredLanguage
     */
    public function __construct(array $preferredLanguage)
    {
        $this->preferredLanguage = $preferredLanguage;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $request = $event->getRequest();
        $request->setLocale($request->getPreferredLanguage($this->preferredLanguage));
    }
}