<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/language", name="app_language_selection")
     */
    public function getLanguageAction(Request $request)
    {
        $language = $request->get('language');
        $session = $request->getSession();
        $session->set('_locale', $language);

        return $this->redirect($request->headers->get('referer'));
    }

}
