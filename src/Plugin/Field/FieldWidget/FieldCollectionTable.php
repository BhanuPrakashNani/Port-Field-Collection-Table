<?php

/**
 * @file
 * Contains \Drupal\field_collection_table\Plugin\Field\FieldWidget\FieldCollectionTable.
 */

namespace Drupal\field_collection_table\Plugin\Field\FieldWidget;

use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Annotation\Plugin;
use Drupal\Core\Annotation\Translation;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Symfony\Component\Validator\ConstraintViolationInterface;

/**
 * @FieldWidget(
 *  id = "field_collection_table",
 *  label = @Translation("Table"),
 *  field_types = {"field_collection"}
 * )
 */
class FieldCollectionTable extends WidgetBase implements WidgetInterface  {

  /**
   * {@inheritdoc}
   */
  public function defaultSettings() {
    return [
      'nodragging' => FALSE,
      'hide_title' => FALSE,
      'placeholder' => '',
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, array &$form_state) {

    $element = [];

    $element['nodragging'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable drag and drop'),
      '#description' => $this->t('If checked, users cannot rearrange the rows.'),
      '#default_value' => $this->getSetting('nodragging'),
      '#weight' => 1,
    ];

    $element['hide_title'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Hide title'),
      '#description' => $this->t('If checked, the field title will be hidden.'),
      '#default_value' => $this->getSetting('hide_title'),
      '#weight' => 2,
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state)  {

    $element = parent::formElement($items, $delta, $element, $form, $form_state);

/**    $main_widget = $element + [
      '#type' => 'textfield',
      '#default_value' => isset($items[$delta]->value) ? $items[$delta]->value : NULL,
      '#size' => $this->getSetting('size'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#maxlength' => $this->getSetting('maxlength'),
      '#attributes' => ['class' => ['text-full']],
    ];

    // add field_collection_field_widget_form settings

*/

  }

  /**
   * {@inheritdoc}
   */
  public function errorElement(array $element, ConstraintViolationInterface $violation, array $form, array &$form_state)  {
    return $element[$violation->arrayPropertyPath[0]];
  }

}
