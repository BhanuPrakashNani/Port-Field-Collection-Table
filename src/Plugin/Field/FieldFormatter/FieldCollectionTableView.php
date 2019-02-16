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

  public function settingsForm(array $form, FormStateInterface $form_state) {

    $element = [];

    $element['hide_empty'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide empty collection'),
      '#default_value' => $this->getSetting('hide_empty'),
      '#description' => t('If enabled, nothing will be displayed for an empty collection (not even the add link).'),
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
      '#states'=> ['visible' => [':input[name="fields[field_fc][settings_edit_form][settings][orientation]"]' => ['value' => 'rows']]],
    ];

    return $element;
  }



}
