<?php

namespace Drupal\gdpr_compliance\Hook;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * PreprocessHtml.
 */
class PageBottom extends ControllerBase {

  /**
   * Hook.
   */
  public static function hook(array &$page_bottom) {
    $path = \Drupal::service('path.current')->getPath();
    $config = \Drupal::config('gdpr_compliance.settings');
    // Don't display on admin pages.
    if (substr($path, 0, 7) != '/admin/' && self::display($config)) {
      $popup_color = '';
      $button_color = '';
      if ($config->get('popup-custom-color')) {
        list($r, $g, $b) = sscanf($config->get('popup-color'), "#%02x%02x%02x");
        $popup_color = "style=\"background:rgba($r, $g, $b, 0.9);\"";
        $button_hex = $config->get('button-color');
        $button_color = "style=background:$button_hex;";
      }

      // Generate url for 'More information' link.
      $link = $config->get('popup-morelink');
      if (substr($link, 0, 1) == '/') {
        // A path should be handled as user input.
        $url = Url::fromUserInput($link);
      }
      else {
        // An external url should use 'fromUri'.
        $url = Url::fromUri($link);
      }

      $page_bottom['gdpr-popup'] = [
        '#theme' => 'gdpr-popup',
        '#data' => [
          'popup_morelink' => $url,
          'popup_position' => $config->get('popup-position'),
          'popup_color' => $popup_color,
          'popup_text' => $config->get('popup-text'),
          'button_color' => $button_color,
          'button_text' => $config->get('button-text'),
        ],
        '#attached' => [
          'library' => ['gdpr_compliance/popup'],
        ],
      ];
    }
  }

  /**
   * Display Rules.
   */
  public static function display($config) {
    $display = FALSE;
    if (\Drupal::currentUser()->id()) {
      if ($config->get('popup-users')) {
        $display = TRUE;
      }
    }
    elseif ($config->get('popup-guests')) {
      $display = TRUE;
    }
    return $display;
  }

}
