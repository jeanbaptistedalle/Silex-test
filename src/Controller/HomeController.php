<?php

namespace Test\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class HomeController {

    /**
     * Home page controller.
     *
     * @param Application $app Silex application
     */
    public function indexAction(Application $app) {
        $characters = $app['dao.character']->findAll();
        return $app['twig']->render('index.html.twig', array('characters' => $characters));
    }

    /**
     * User login controller.
     *
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function loginAction(Request $request, Application $app) {
        return $app['twig']->render('login.html.twig', array(
                    'error' => $app['security.last_error']($request),
                    'last_username' => $app['session']->get('_security.last_username'),
        ));
    }

    public function getFlashBagJson(Request $request, Application $app) {
        $messages = array();
        $cpt = 0;
        foreach (['info', 'warning', 'error', 'success'] as $level) {
            foreach ($app['session']->getFlashbag()->get($level) as $message) {
                $messages[$cpt]['level'] = $level;
                $messages[$cpt]['message'] = $message;
            }
            $cpt++;
        }
        return $app->json($messages);
    }

}
