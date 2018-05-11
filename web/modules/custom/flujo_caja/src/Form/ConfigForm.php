<?php

namespace Drupal\flujo_caja\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class ConfigForm.
 */
class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'flujo_caja.config',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('flujo_caja.config');
    $form['zona'] = [
      '#type' => 'number',
      '#title' => $this->t('Zona'),
      '#description' => $this->t('Porcentaje correspondiente a la zona'),
      '#default_value' => $config->get('zona'),
    ];
    $form['diocesis'] = [
      '#type' => 'number',
      '#title' => $this->t('Diocesis'),
      '#description' => $this->t('Porcentaje que se da a la Diocesis'),
      '#default_value' => $config->get('diocesis'),
    ];
    $form['sacerdotes'] = [
      '#type' => 'number',
      '#title' => $this->t('Sacerdotes'),
      '#description' => $this->t('Porcentaje que se da a los sacerdotes'),
      '#default_value' => $config->get('sacerdotes'),
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

    $this->config('flujo_caja.config')
      ->set('zona', $form_state->getValue('zona'))
      ->set('diocesis', $form_state->getValue('diocesis'))
      ->set('sacerdotes', $form_state->getValue('sacerdotes'))
      ->save();
  }

}
