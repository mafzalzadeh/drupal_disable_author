<?php


/**
 * @file
 * Contains Drupal\welcome\Form\MessagesForm.
 */


namespace Drupal\disable_author\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;


class ConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'disable_author.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'disable_author_admin_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('disable_author.settings');
    $roles = user_role_names();
    unset($roles['administrator']);

    $form['roles'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('The Roles should not see the author form'),
      '#default_value' => $config->get('disallowed_roles'),
      '#options' => $roles,
    ];

    $form['array_filter'] = ['#type' => 'value', '#value' => TRUE];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $disallowed_roles = array_filter($form_state->getValue('roles'));
    sort($disallowed_roles);
    $this->config('disable_author.settings')
      ->set('disallowed_roles',$disallowed_roles)
    ->save();
    parent::submitForm($form, $form_state);

  }


}
