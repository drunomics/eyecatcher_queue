<?php

namespace Drupal\eyecatcher_queue\Routing;

use Drupal\eyecatcher_queue\Service\EyecatcherServiceTrait;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Eyecatcher RouteSubscriber.
 */
class EyecatcherRoutes {

  use EyecatcherServiceTrait;

  /**
   * {@inheritdoc}
   */
  public function routes() {
    $collection = new RouteCollection();

    foreach ($this->getEyecatcherQueueService()->getEyecatcherQueues() as $queue) {
      $collection->add('eyecatcher_queue.eyecatcher.config.' . $queue->id(), new Route(
        '/admin/config/content/eyecatcher/' . $queue->id(),
        [
          '_title' => $queue->label(),
          '_controller' => '\Drupal\eyecatcher_queue\Controller\EyecatcherConfigController::eyecatcherQueueConfig',
        ],
        [
          '_permission' => 'update ' . $queue->id() . ' entityqueue',
        ]
      ));
    }

    return $collection;
  }

}
