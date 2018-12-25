<?php

/**
 * Print a single row of multiple fields.
 */
function theme_field_collection_table_multiple_value_field($variables) {
  $element = $variables['element'];
  $header = array();
  $cells = array();

  // Order field widgets by their widget weight.
  $instances = field_info_instances($element['#entity_type'], $element['#bundle']);
  uasort($instances, '_field_collection_table_sort_items_widget_helper');
  foreach (array_keys($instances) as $field_name) {
    if (empty($element[$field_name])) {
      continue;
    }

    if (!isset($element[$field_name]['#access']) || $element[$field_name]['#access']) {
      $header[] = _field_collection_table_get_title($element[$field_name]);
      $cells[] = array('data' => $element[$field_name]);
    }

    // Remove the original field to prevent duplicate printing.
    unset($element[$field_name]);
  }

  $element['field_collection_table'] = array(
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => array($cells),
    '#weight' => 0,
  );

  $element['#sorted'] = FALSE;
  return drupal_render_children($element);
}

/**
 * Comparison function for sorting field instances by their widget's weight.
 */
function _field_collection_table_sort_items_widget_helper($a, $b) {
  $a_weight = (is_array($a) && isset($a['widget']['weight']) ? $a['widget']['weight'] : 0);
  $b_weight = (is_array($b) && isset($b['widget']['weight']) ? $b['widget']['weight'] : 0);
  return $a_weight - $b_weight;
}

/**
 * Replacement for theme_field_multiple_value_form().
 *
 * Each field is printed in a separate cell.
 */
function theme_field_collection_table_multiple_value_fields($variables) {
  $element = $variables['element'];
  if (!isset($element['#custom_settings']['hide_title'])) {
    $element['#custom_settings']['hide_title'] = FALSE;
  }
  if (!isset($element['#custom_settings']['nodragging'])) {
    $element['#custom_settings']['nodragging'] = TRUE;
  }
  if (isset($element['#cardinality']) && ($element['#cardinality'] > 1 || $element['#cardinality'] == FIELD_CARDINALITY_UNLIMITED)) {
    $table_id = \Drupal\Component\Utility\Html::getId($element['#field_name'] . '_values');
    $order_class = $element['#field_name'] . '-delta-order';
    // Sort items according to '_weight'.
    // Needed when the form comes back after preview or failed validation.
    $items = array();
    foreach (\Drupal\Core\Render\Element::children($element) as $key) {
      if (!isset($element[$key]['#entity_type'])) {
        if ($key === 'add_more') {
          $add_more_button = &$element[$key];
        }
      }
      else {
        $items[] = &$element[$key];
      }
    }

    $header = array();
    $cells_default = array();
    if (!$element['#custom_settings']['nodragging']) {
      usort($items, '_field_sort_items_value_helper');
      $header = array(array('data' => '', 'class' => 'tabledrag'));
      $cells_default = array(array('data' => '', 'class' => 'field-multiple-drag'));
    }

    $rows = array();
    foreach ($items as $key => $item) {
      uasort($item, 'element_sort');
      $item['_weight']['#attributes']['class'] = array($order_class);
      $cells = $cells_default;
      foreach (\Drupal\Core\Render\Element::children($item) as $field_name) {
        if (!$element['#custom_settings']['nodragging'] || $field_name != '_weight') {
          if (!isset($item[$field_name]['#access']) || $item[$field_name]['#access']) {
            if ($key == 0) {
              $header[] = array(
                'data' => '<label>' . t('!title', array('!title' => _field_collection_table_get_title($item[$field_name]))) . '</label>',
                'class' => array('field-label', \Drupal\Component\Utility\Html::getClass($field_name)),
              );
            }
            $cells[] = array(
              'data' => $item[$field_name],
              'class' => \Drupal\Component\Utility\Html::getClass($field_name),
            );
          }
        }
      }
      if (!$element['#custom_settings']['nodragging']) {
        $rows[] = array('data' => $cells, 'class' => array('draggable'));
      }
      else {
        $rows[] = array('data' => $cells);
      }
    }

    $output = array(
      '#prefix' => '<div class="form-item">',
      '#suffix' => '</div>',
    );

    if (!$element['#custom_settings']['hide_title']) {
      // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $output['title'] = array(
//         '#prefix' => "<label class='form-item-title'>",
//         '#markup' => t('!title !required', array(
//           '!title' => $element['#title'],
//           '!required' => !empty($element['#required']) ? theme('form_required_marker', array('element' => $element)) : '',
//         )),
//         '#suffix' => '</label>',
//       );

    }

    $output['field_collection_table'] = array(
      '#theme' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#weight' => 20,
      '#attributes' => array(
        'id' => $table_id,
        'class' => array(
          'field-multiple-table',
        ),
      ),
    );
    if (!empty($element['#description'])) {
      $output[] = array(
        '#prefix' => '<div class="description">',
        '#suffix' => '</div>',
        '#markup' => $element['#description'],
        '#weight' => 30,
      );
    }
    if (isset($add_more_button)) {
      $add_more_button['#weight'] = 40;
      $output[] = $add_more_button;
    }
    if (!$element['#custom_settings']['nodragging']) {
      // @FIXME
// TableDrag is now attached with the #tabledrag property of certain render
// arrays. drupal_add_tabledrag() is now internal and should never be called directly.
// 
// 
// @see https://www.drupal.org/node/2160571
// drupal_add_tabledrag($table_id, 'order', 'sibling', $order_class);

    }
    $output = \Drupal::service("renderer")->render($output);
  }
  else {
    $output = '';
    foreach (\Drupal\Core\Render\Element::children($element) as $key) {
      $output .= \Drupal::service("renderer")->render($element[$key]);
    }
  }

  return $output;
}

/**
 * Preprocess variables for a field collection table.
 */
function template_preprocess_table__field_collection_table(&$variables) {
  if (empty($variables['settings'])) {
    return;
  }
  if (isset($variables['settings']['empty'])) {
    _field_collection_table_hide_empty($variables);
  }
}

/**
 * Remove columns that are entirely empty.
 */
function _field_collection_table_hide_empty(&$variables) {
  $rows = $variables['rows'];

  $count = array();
  foreach ($rows as $row_delta => $row) {
    foreach ($row['data'] as $column_delta => $column) {
      if (!isset($count[$column_delta])) {
        $count[$column_delta] = 0;
      }
      if (isset($column['data']['#empty'])) {
        $count[$column_delta]++;
      }
    }
  }
  foreach ($count as $column_delta => $column) {
    if ($column === count($rows)) {
      foreach ($rows as $row_delta => $row) {
        unset($variables['rows'][$row_delta]['data'][$column_delta]);
        unset($variables['header'][$column_delta]);
      }
    }
  }
}

/**
 * Derivative of theme_table() solely for the HOOK_preprocess_table__PATTERN().
 */
function theme_table__field_collection_table($variables) {
  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// return theme('table', $variables);

}

/**
 * Helps find the title of the field, as it could be in several places.
 */
function _field_collection_table_get_title($field) {
  $title = '';
  $required = FALSE;

  if (isset($field['#language']) && isset($field[$field['#language']])) {
    $language = $field['#language'];
    if (isset($field[$language]['#title'])) {
      $title = $field[$language]['#title'];
      $required = !empty($field[$language]['#required']);

    }
    elseif (isset($field[$language][0]['#title'])) {
      $title = $field[$language][0]['#title'];
      $required = !empty($field[$language][0]['#required']);
    }
    elseif (isset($field[$language]['#type']) && $field[$language]['#type'] == 'select_or_other') {
      $title = $field[$language]['select']['#title'];
      $required = !empty($field[$language]['select']['#required']);
    }
  }

  elseif (isset($field['#title'])) {
    $title = empty($field['#is_weight']) ? $field['#title'] : t('Order');
    $required = !empty($field['#required']);
  }
  elseif (isset($field['#value'])) {
    $title = $field['#value'];
    $required = !empty($field['#required']);
  }
  // @FIXME
// theme() has been renamed to _theme() and should NEVER be called directly.
// Calling _theme() directly can alter the expected output and potentially
// introduce security issues (see https://www.drupal.org/node/2195739). You
// should use renderable arrays instead.
// 
// 
// @see https://www.drupal.org/node/2195739
// $required = $required ? theme('form_required_marker', array('element' => $field)) : '';

  return t('!title !required', array(
    '!title' => $title,
    '!required' => $required,
  ));
}
