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
          $element.readmore($.extend(settings.readmoreSettings[id], {
            beforeToggle: function(trigger, $element, expanded) {
              $element.trigger('readmore.beforeToggle', [trigger, expanded])
            },
            afterToggle: function(trigger, $element, expanded) {
              $element.trigger('readmore.afterToggle', [trigger, expanded])
            }
          }));
        }
      }
    }
  };
})(jQuery, Drupal);
