/**
 * @file
 * Attaches behaviors to initialize readmore js.
 */

(function($, Drupal) {

  'use strict';

  /**
   * Initialize readmore js.
   *
   * @type {Drupal~behavior}
   */
  Drupal.behaviors.readmoreSettings = {
    attach: function(context, settings) {
      for (var id in settings.readmoreSettings) {
        if (settings.readmoreSettings.hasOwnProperty(id)) {
          var $element = $('.' + id, context);
          $element.readmore(settings.readmoreSettings[id]);
        }
      }
    }
  };
})(jQuery, Drupal);
