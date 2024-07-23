<?php

namespace Drupal\first_module\Controller;

use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {
  /**
   * Returns a simple page.
   *
   * @return array
   *   A render array containing the message.
   */
  public function content() {
    return [
      '#type' => 'markup',
      '#markup' => t('Hello World'),
    ];
  }
}