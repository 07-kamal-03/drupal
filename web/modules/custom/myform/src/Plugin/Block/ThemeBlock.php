<?php

namespace Drupal\myform\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provide "ThemeBlock" block
 * 
 * @Block(
 * id = "ThemeBlock",
 * admin_label = @Translation("ThemeBlock"),
 * category = @Translation("ThemeBlock")
 * )
 */
class ThemeBlock extends BlockBase {

    public function build() {
      // You can fetch and prepare data here to pass to the Twig template.
      $variables = [
        'con' => $this->t('This is your custom block content.'),
      ];
  
      return [
        '#theme' => 'block--firstcustomtheme--themeblock',
        '#variables' => $variables,
      ];
    }
  
  }


?>