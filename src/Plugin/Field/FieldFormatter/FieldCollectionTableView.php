<?php

/**
 * @file
 * Contains \Drupal\field_collection_table\Plugin\Field\FieldFormatter\FieldCollectionTableView.
 */

namespace Drupal\field_collection_table\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @FieldFormatter(
 *  id = "field_collection_table_view",
 *  label = @Translation("Table of field collection items"),
 *  field_types = {"field_collection"}
 * )
 */
class FieldCollectionTableView extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {

    $element = [];
    $field_options = ['none' => $this->t('None')];

    $element['hide_empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide empty collection'),
      '#default_value' => $this->getSetting('hide_empty'),
      '#description' => $this->t('If enabled, nothing will be displayed for an empty collection (not even the add link).'),
    ];

    $element['empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide empty columns'),
      '#description' => $this->t('If checked, hides empty table columns.'),
      '#default_value' => $this->getSetting('empty'),
    ];

    $element['caption'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Table caption'),
      '#description' => $this->t('Displayed in the caption element above the table'),
      '#default_value' => $this->getSetting('caption'),
    ];

    $element['orientation'] = [
      '#type' => 'select',
      '#title' => $this->t('Orientation'),
      '#description' => $this->t('Set the orientation of the table'),
      '#options' => [
        'columns' => $this->t('Columns'),
        'rows' => $this->t('Rows'),
      ],
      '#default_value' => $this->getSetting('orientation'),
    );

    $element['header_column'] = [
      '#type' => 'select',
      '#title' => $this->t('Header field'),
      '#description' => $this->t('The selected field value will be used as the horizontal table header'),
      '#options' => $field_options,
      '#states'=> ['visible' => [':input[name="fields[field_fc]
      [settings_edit_form][settings][orientation]"]' => ['value' => 'rows']]],
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $element = [];
    $settings = $this->getFieldSettings();

    foreach ($items as $delta => $item) {
      // Render each element as markup.
      $element[$delta] = ['#markup' => $item->value];
    }

    if (empty($items) && !empty($this->getSetting('hide_empty'))) {
      return $element;
    }

/**
// todo: modify the table method callback functions to sync with d8
    if ($settings['orientation'] === 'columns') {
      _field_collection_table_column_mode($element, $settings, $entity_type,
        $entity, $field, $instance, $langcode, $items, $display);
    }
    if ($settings['orientation'] === 'rows') {
      _field_collection_table_row_mode($element, $settings, $entity_type,
        $entity, $field, $instance, $langcode, $items, $display);
    }
*/

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary()  {

    $output = settingsSummary();

    $output .= '<br>';
    $output .= !empty($this->getSetting('hide_empty')) ? $this->t('Empty collections: Hidden') : $this->t('Empty collections: Shown');
    $output .= '<br>';
    $output .= !empty($this->getSetting('empty')) ? $this->t('Empty columns: Hidden') : $this->t('Empty columns: Shown');
    $output .= !empty($this->getSetting('caption')) ? '<br>' . $this->t('Caption: %caption', ['%caption' => $this->t($this->getSetting('caption'))]) : '';
    $orientations = ['columns' => $this->t('Column'), 'rows' => $this->t('Row')];
    $output .= '<br />';
    $output .= !empty($this->getSetting('empty')) ? $this->t('Empty columns: Hidden') : $this->t('Empty columns: Shown');
    if (isset($this->getSetting('orientation'))) {
      $output .= '<br />';
      $output .= $this->t('Format fields as <strong>!orientation</strong>.', ['!orientation' => $orientations[$this->getSetting('orientation')]]);
    }
    if (isset($this->getSetting('orientation')) && $this->getSetting('orientation') === 'rows') {
      $output .= '<br />';
      if (isset($this->getSetting('header_column')) && $this->getSetting('header_column') !== 'none') {
        $output .= '<br />';
        $output .= $this->t('Field @field value is used as the header', ['@field' => $this->getSetting('header_column')]);
      }
    }

    return $output;
  }




}
