<?php

namespace Test\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Test\Domain\Character;
use Test\Form\Type\CharacterType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;

class CharacterController {

    public function characterDetail($id, Application $app) {
        $character = $app['dao.character']->find($id);
        return $app['twig']->render('character.html.twig', array(
                    'character' => $character));
    }

    public function characterList(Application $app) {
        $characters = $app['dao.character']->findAll();
        return new JsonResponse(array('html' => $app['twig']->render('_character_list.html.twig', array('characters' => $characters))));
    }

    public function deleteCharacterAction($id, Application $app) {
        $app['dao.character']->delete($id);
        return $app->redirect($app["url_generator"]->generate("home"));
    }

    public function prepareCharacterFormAjax($id, Request $request, Application $app) {
        $character = $app['dao.character']->find($id);
        if ($character) {
            $title = "Editer un personnage";
            $button_label = "Sauvegarder";
        } else {
            $character = new Character();
            $title = "Créer un personnage";
            $button_label = "Créer";
        }
        $form = $app['form.factory']->create(new CharacterType(), $character);
        $form->handleRequest($request);
        return $app['twig']->render('character_form.html.twig', array(
                    'title' => $title,
                    'button_label' => $button_label,
                    'form' => $form->createView()));
    }

    public function characterFormAjax(Request $request, Application $app) {
        $character = new Character();
        $form = $app['form.factory']->create(new CharacterType(), $character);
        $form->handleRequest($request);
        if ($app['dao.character']->nameAlreadyExists($character)) {
            $form->addError(new FormError('Le nom de personnage saisi existe déjà'));
        }
        if ($form->isValid()) {
            $id = $character->getId();
            $app['dao.character']->save($character);
            if ($id) {
                $app['session']->getFlashBag()->add('success', 'Le personnage a été modifié.');
            } else {
                $app['session']->getFlashBag()->add('success', 'Le personnage a été créé.');
            }
            $response = new JsonResponse(
                    array('reload' => true)
            );
        } else {
            $response = new JsonResponse(
                    array('html' => $app['twig']->render('_character_form.html.twig', array('form' => $form->createView())))
            );
        }
        return $response;
    }

}
