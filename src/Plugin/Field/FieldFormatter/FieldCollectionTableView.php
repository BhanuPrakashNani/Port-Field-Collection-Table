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
  $element = [];
  $field_options = array('none' => $this->t('None'));

  $options = array(
    'columns' => $this->t('Columns'),
    'rows' => $this->t('Rows') );

  $element['hide_empty'] = array(
    '#type' => 'checkbox',
    '#title' => $this->t('Hide empty collection'),
    '#default_value' => $this->getSettings('hide_empty'),
    '#description' => $this->t('If enabled, nothing will be displayed for an empty collection (not even the add link).'),
  );
  $element['empty'] = array(
    '#type' => 'checkbox',
    '#title' => $this->t('Hide empty columns'),
    '#description' => $this->t('If checked, hides empty table columns.'),
    '#default_value' => $this->getSettings('empty'),
  );
  $element['caption'] = array(
    '#type' => 'textfield',
    '#title' => $this->t('Table caption'),
    '#description' => $this->t('Displayed in the caption element above the table'),
    '#default_value' => $this->getSettings('caption'),
  );
  $element['orientation'] = array(
    '#type' => 'select',
    '#title' => $this->t('Orientation'),
    '#description' => $this->t('Set the orientation of the table'),
    '#options' => $options,
    ),
    '#default_value' => $this->getSettings('orientation'),
  );

  $element['header_column'] = array(
    '#type' => 'select',
    '#title' => $this->t('Header field'),
    '#description' => $this->t('The selected field value will be used as the horizontal table header'),
    '#options' => $field_options,
    '#states'=> array('visible' => array(':input[name="fields[field_fc][settings_edit_form][settings][orientation]"]' => array('value' => 'rows'))),
  );

  return $element;
   }

}
