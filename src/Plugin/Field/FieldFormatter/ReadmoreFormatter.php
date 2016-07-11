<?php

namespace Drupal\readmore_formatter\Plugin\Field\FieldFormatter;

use Drupal\Component\Utility\Html;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\text\Plugin\Field\FieldFormatter\TextDefaultFormatter;

/**
 * Plugin implementation of the 'readmore_formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "readmore_formatter",
 *   label = @Translation("Read more formatter"),
 *   field_types = {
 *     "text",
 *     "text_long",
 *     "text_with_summary"
 *   }
 * )
 */
class ReadmoreFormatter extends TextDefaultFormatter {

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      // Add default settings with text, so we can wrap them in t().
      // in milliseconds.
      'speed' => 100,
      'collapsedHeight' => 75,
      // In pixel.
      'heightMargin' => 16,
      'moreLink' => '<a href="#">' . t('Read more') . '</a>',
      'lessLink' => '<a href="#">' . t('Close') . '</a>',
      'embedCSS' => 1,
      'sectionCSS' => 'display: block; width: 100%;',
      'startOpen' => 0,
      'expandedClass' => 'readmore-js-expanded',
      'collapsedClass' => 'readmore-js-collapsed',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    $element['speed'] = [
      '#type' => 'number',
      '#min' => 1,
      '#title' => $this->t('Speed'),
      '#description' => $this->t('Speed for show / hide read more.'),
      '#default_value' => $this->getSetting('speed'),
    ];

    $element['collapsedHeight'] = [
      '#type' => 'number',
      '#min' => 1,
      '#title' => $this->t('Collapsed Height'),
      '#description' => $this->t('Height after which readmore will be added.'),
      '#default_value' => $this->getSetting('collapsedHeight'),
    ];

    $element['heightMargin'] = [
      '#type' => 'number',
      '#min' => 1,
      '#title' => $this->t('Height margin'),
      '#description' => $this->t('Avoids collapsing blocks that are only slightly larger than maxHeight.'),
      '#default_value' => $this->getSetting('heightMargin'),
    ];

    $element['moreLink'] = [
      '#type' => 'textfield',
      '#title' => $this->t('More link'),
      '#description' => $this->t('Link for more.'),
      '#default_value' => $this->getSetting('moreLink'),
    ];

    $element['lessLink'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Less link'),
      '#description' => $this->t('Link for less.'),
      '#default_value' => $this->getSetting('lessLink'),
    ];

    $element['embedCSS'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Embed CSS'),
      '#description' => $this->t('Insert required CSS dynamically, set this to false if you include the necessary CSS in a stylesheet.'),
      '#default_value' => $this->getSetting('embedCSS'),
    ];

    $element['sectionCSS'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Section styling'),
      '#description' => $this->t('Sets the styling of the blocks, ignored if embedCSS is false).'),
      '#default_value' => $this->getSetting('sectionCSS'),
    ];

    $element['startOpen'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Start open'),
      '#description' => $this->t('Do not immediately truncate, start in the fully opened position.'),
      '#default_value' => $this->getSetting('startOpen'),
    ];

    $element['expandedClass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Expanded class'),
      '#description' => $this->t('Class added to expanded blocks.'),
      '#default_value' => $this->getSetting('expandedClass'),
    ];

    $element['collapsedClass'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Collapsed class'),
      '#description' => $this->t('Class added to collapsed blocks.'),
      '#default_value' => $this->getSetting('collapsedClass'),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    $summary[] = $this->t('Speed: @value', ['@value' => $this->getSetting('speed')]);
    $summary[] = $this->t('Collapsed Height: @value', ['@value' => $this->getSetting('collapsedHeight')]);
    $summary[] = $this->t('Height margin: @value', ['@value' => $this->getSetting('heightMargin')]);
    $summary[] = $this->t('More link: @value', ['@value' => $this->getSetting('moreLink')]);
    $summary[] = $this->t('Less link: @value', ['@value' => $this->getSetting('lessLink')]);
    $summary[] = $this->t('Embed CSS: @value', ['@value' => $this->getSetting('embedCSS')]);
    $summary[] = $this->t('Section styling: @value', ['@value' => $this->getSetting('sectionCSS')]);
    $summary[] = $this->t('Start open: @value', ['@value' => $this->getSetting('startOpen')]);
    $summary[] = $this->t('Expanded class: @value', ['@value' => $this->getSetting('expandedClass')]);
    $summary[] = $this->t('Collapsed class: @value', ['@value' => $this->getSetting('collapsedClass')]);

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);
    $settings = $this->getSettings();
    $integer_fields = [
      'speed',
      'collapsedHeight',
      'heightMargin',
      'embedCSS',
      'startOpen',
    ];
    foreach ($integer_fields as $key) {
      $settings[$key] = (int) $settings[$key];
    }
    $field_name = $items->getFieldDefinition()->getName();
    foreach ($items as $delta => $item) {
      $unique_id = Html::getUniqueId('field-readmore-' . $field_name);
      $elements[$delta]['#prefix'] = '<div class="field-readmore ' . $unique_id . '">';
      $elements[$delta]['#suffix'] = '</div>';
      $elements[$delta]['#attached']['library'][] = 'readmore_formatter/readmore';
      $elements[$delta]['#attached']['drupalSettings']['readmoreSettings'][$unique_id] = $settings;
    }
    return $elements;
  }

}
