<?php /**
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

/**
 * @FieldWidget(
 *  id = "field_collection_table",
 *  label = @Translation("Table"),
 *  field_types = {"field_collection"}
 * )
 */
class FieldCollectionTable extends WidgetBase {

  /**
   * @FIXME
   * Move all logic relating to the field_collection_table widget into this class.
   * For more information, see:
   *
   * https://www.drupal.org/node/1796000
   * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21WidgetInterface.php/interface/WidgetInterface/8
   * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21WidgetBase.php/class/WidgetBase/8
   */

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, array &$form_state) {

    $element['nodragging'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Disable drag and drop'),
      '#description' => $this->t('If checked, users cannot rearrange the rows.'),
      '#default_value' => $settings['nodragging'],
      '#weight' => 2,
    );

    $element['hide_title'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Hide title'),
      '#description' => $this->t('If checked, the field title will be hidden.'),
      '#default_value' => $this->getSetting['hide_title'],
      '#weight' => 3,
    );

    return $element;

  }



}
