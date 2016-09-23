<?php

namespace Test\Form\Type;

use Silex\Application;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CharacterType extends AbstractType {

    public function __construct() {
        
    }

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
                ->setMethod('POST')
                /*->setAction($this->url)*/
                ->add('id', 'hidden')
                ->add('name', 'text', array('label' => 'Nom'));
    }

    public function getName() {
        return 'character';
    }

    /* private $url;



      public static function __constructWithUrl($url) {
      $instance = new self();
      $instance->setUrl($url);
      return $instance;
      }



      public function setUrl($url) {
      $this->url = $url;
      } */
}
