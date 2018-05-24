<?php

namespace Drupal\galeria_facebook\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Class DefaultController.
 */
class DefaultController extends ControllerBase {

  /**
   * Main.
   *
   * @return string
   *   Return Main string.
   */
  public function main() {
    $data = [];
    #Datos de Configuracion
    $config = \Drupal::config('galeria_facebook.settings');
    $appId = $config->get('appid');
    $appSecret = $config->get('app_secret');
    $access_token = $config->get('access_token');
    $fields = $config->get('page_fields');
    $fb_page_id = $config->get('pageid');
    $sw_token = $config->get('usar_access_token');

    $graphAlbLink = "https://graph.facebook.com/v3.0/{$fb_page_id}/albums?fields={$fields}&access_token={$access_token}";

    $jsonData = file_get_contents($graphAlbLink);
    $fbAlbumObj = json_decode($jsonData, true, 512, JSON_BIGINT_AS_STRING);

    // Facebook albums content
    $fbAlbumData = $fbAlbumObj['data'];
    $data['albums'] = $fbAlbumData;
    $data['token'] = $access_token;
print_r($data);
    return [
      '#theme' => 'galeria_facebook',
      '#data' => $data,
      '#attached'=>[
            'library'=>[
              'galeria_facebook/galeria_facebook.librerias'
            ]
        ]
    ];
  }

}
