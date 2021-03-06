<?php

namespace Drupal\gdpr_compliance\Hook;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\gdpr_compliance\Controller\FormWarning;

/**
 * Hook hook_form_user_register_alter().
 */
class FormUserRegisterFormAlter extends ControllerBase {

  /**
   * Hook.
   */
  public static function hook(&$form, FormStateInterface $form_state, $form_id) {
    if (\Drupal::config('gdpr_compliance.settings')->get('user-register')) {
      FormWarning::addWarning($form);
    };
  }

}
