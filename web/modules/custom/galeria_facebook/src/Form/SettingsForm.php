<?php

namespace Drupal\galeria_facebook\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class SettingsForm.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'galeria_facebook.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('galeria_facebook.settings');
    $form['group1'] = array(
      '#type' => 'details',
      '#title' => $this->t('Datos de acceso al Facebook'),
    );
    $form['group1']['appid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('AppId'),
      '#description' => $this->t('Id de la aplicacion'),
      '#maxlength' => 64,
      '#size' => 64,
      '#default_value' => $config->get('appid'),
    ];
    $form['group1']['app_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App Secret'),
      '#description' => $this->t('Frase Secreta de la aplicacion'),
      '#maxlength' => 150,
      '#size' => 64,
      '#default_value' => $config->get('app_secret'),
    ];
    $form['group1']['usar_access_token'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Usar Access Token'),
      '#description' => $this->t('Seleccione si desea usar el token proporcionado, de lo contrario obtendra el token con las credenciales del app facebook'),
      '#default_value' => $config->get('usar_access_token'),
    ];
    $form['group1']['access_token'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Access Token'),
      '#description' => $this->t('Token de Acceso'),
      '#maxlength' => 255,
      '#size' => 64,
      '#default_value' => $config->get('access_token'),
    ];
    $form['group2'] = array(
      '#type' => 'details',
      '#title' => $this->t('Datos de Pagina de Facebook'),
    );
    $form['group2']['pageid'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Id Page Facebook'),
      '#description' => $this->t('Ingresar id de la pagina o escribir (me) para mi propio facebook'),
      '#maxlength' => 150,
      '#size' => 64,
      '#default_value' => $config->get('pageid'),
    ];
    $form['group2']['page_fields'] = [
      '#type' => 'textfield',
      '#title' => $this->t('fields the page'),
      '#description' => $this->t('Campos que se extraeran de la pagina de facebook'),
      '#maxlength' => 255,
      '#size' => 150,
      '#default_value' => $config->get('page_fields'),
    ];
    $form['group3'] = array(
      '#type' => 'details',
      '#title' => $this->t('Datos de Galeria'),
    );
    $form['group3']['number_albums'] = [
      '#type' => 'number',
      '#title' => $this->t('Cantidad de albunes'),
      '#description' => $this->t('Muestra la cantidad de albums que se mostrar'),
      '#maxlength' => 10,
      '#size' => 15,
      '#default_value' => $config->get('number_albums'),
    ];
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('galeria_facebook.settings')
      ->set('appid', $form_state->getValue('appid'))
      ->set('app_secret', $form_state->getValue('app_secret'))
      ->set('access_token', $form_state->getValue('access_token'))
      ->set('usar_access_token', $form_state->getValue('usar_access_token'))
      ->set('pageid', $form_state->getValue('pageid'))
      ->set('page_fields', $form_state->getValue('page_fields'))
      ->set('number_albums', $form_state->getValue('number_albums'))
      ->save();
  }

}
