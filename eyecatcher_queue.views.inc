<?php

/**
 * @file
 * Provide views data for our custom module_name.
 */

/**
 * Implements hook_views_data().
 */
function eyecatcher_queue_views_data() {
  $data['views']['eyecatcher_queue'] = [
    'title' => t('Eyecatcher'),
    'help' => t('Renders an eyecatcher from the eyecatcher queue in this area.'),
    'area' => [
      'id' => 'eyecatcher_queue_eyecatcher',
    ],
  ];
  return $data;
}
