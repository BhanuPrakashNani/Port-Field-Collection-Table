<?php /**
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
   * @FIXME
   * Move all logic relating to the field_collection_table_view formatter into this
   * class. For more information, see:
   *
   * https://www.drupal.org/node/1805846
   * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21FormatterInterface.php/interface/FormatterInterface/8
   * https://api.drupal.org/api/drupal/core%21lib%21Drupal%21Core%21Field%21FormatterBase.php/class/FormatterBase/8
   */

public function settingsForm(array $form, array &$form_state) {
  $element = array();

  $field_collections = field_info_instances('field_collection_item', $instance['field_name']);

  $field_options = array('none' => t('None'));

  foreach ($field_collections as $key => $value) {
    $field_options[$key] = $value['label'];

  $options = array(
    'columns' => t('Columns'),
    'rows' => t('Rows') );

  $element['hide_empty'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide empty collection'),
    '#default_value' => $this->getSettings('hide_empty'),
    '#description' => t('If enabled, nothing will be displayed for an empty collection (not even the add link).'),
  );
  $element['empty'] = array(
    '#type' => 'checkbox',
    '#title' => t('Hide empty columns'),
    '#description' => t('If checked, hides empty table columns.'),
    '#default_value' => $this->getSettings('empty'),
  );
  $element['caption'] = array(
    '#type' => 'textfield',
    '#title' => t('Table caption'),
    '#description' => t('Displayed in the caption element above the table'),
    '#default_value' => $this->getSettings('caption'),
  );
  $element['orientation'] = array(
    '#type' => 'select',
    '#title' => t('Orientation'),
    '#description' => t('Set the orientation of the table'),
    '#options' => $options,
    ),
    '#default_value' => $this->getSettings('orientation'),
  );

  $element['header_column'] = array(
    '#type' => 'select',
    '#title' => t('Header field'),
    '#description' => t('The selected field value will be used as the horizontal table header'),
    '#options' => $field_options,
    '#states'=> array('visible' => array(':input[name="fields[field_fc][settings_edit_form][settings][orientation]"]' => array('value' => 'rows'))),
  );

  return $element;
   }

}
