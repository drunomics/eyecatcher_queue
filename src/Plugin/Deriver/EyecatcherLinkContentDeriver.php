<?php

namespace Drupal\eyecatcher_queue\Plugin\Deriver;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\eyecatcher_queue\Service\EyecatcherServiceTrait;

/**
 * Deriver for generating dynamic menu links for the eyecatcher queues.
 */
class EyecatcherLinkContentDeriver extends DeriverBase {

  use EyecatcherServiceTrait;

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $links = [];
    foreach ($this->getEyecatcherQueueService()->getEyecatcherQueues() as $queue) {
      $id = 'eyecatcher_queue.eyecatcher.config.' . $queue->id();
      $links[$id] = [
        'route_name' => $id,
        'id' => $queue->id(),
        'title' => $queue->label(),
        'parent' => 'eyecatcher_queue.eyecatcher.config',
        'enabled' => 1,
      ];
    }

    return $links;
  }

}
