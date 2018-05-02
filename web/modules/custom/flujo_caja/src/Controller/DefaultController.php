<?php

namespace Drupal\flujo_caja\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Mostrar.
   *
   * @return string
   *   Return Hello string.
   */
  public function mostrar() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: mostrar')
    ];
  }

}
