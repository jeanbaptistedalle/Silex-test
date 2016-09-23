<?php

// Home page
$app->get('/', "Test\Controller\HomeController::indexAction")->bind('home');
// Flashbag (JSON)
$app->post('/flashbag/JSON', "Test\Controller\HomeController::getFlashBagJson")->bind('flashbag_json');
// Login form
$app->get('/login', "Test\Controller\HomeController::loginAction")->bind('login');
// Character delete form
$app->match('/character/{id}/delete', "Test\Controller\CharacterController::deleteCharacterAction")->bind('delete_character');
// Character detail
$app->get('/character/{id}', "Test\Controller\CharacterController::characterDetail")->bind('character');
// Admin zone
$app->get('/admin', "Test\Controller\AdminController::indexAction")->bind('admin');
// Character create/update form (for Ajax call)
$app->post('/character/form/{id}', "Test\Controller\CharacterController::prepareCharacterFormAjax")->value('id', null)->bind('prepare_character_form_ajax');
// Character create/update form (for Ajax call)
$app->post('/character/response/form/', "Test\Controller\CharacterController::characterFormAjax")->bind('character_form_ajax');
// Characters list in table
$app->post('/character/list', "Test\Controller\CharacterController::characterList")->bind('character_list');
