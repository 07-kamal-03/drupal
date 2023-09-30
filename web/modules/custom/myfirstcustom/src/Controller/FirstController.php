<?php
namespace Drupal\myfirstcustom\Controller;

use Drupal\Core\Controller\ControllerBase;
class FirstController extends ControllerBase
{
    public function simpleContent()
    {
        return[
            '#type'=>'markup',
            '#markup'=>t('hi i am kamalraj this is my first custom controller <strong><i>"Hello World"</i></strong>')
        ];
    }
}
?>