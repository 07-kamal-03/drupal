<?php
namespace Drupal\myform\Controller;

use Drupal\Core\Controller\ControllerBase;
class MenuController extends ControllerBase {
    public function mainLinkPage()
    {
        return \Drupal::formBuilder()->getForm('Drupal\myform\Form\DataForm');
    }
}
?>