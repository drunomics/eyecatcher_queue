<?php

namespace Drupal\eyecatcher_queue\Plugin\EntityQueueHandler;

use Drupal\entityqueue\Plugin\EntityQueueHandler\Simple;

/**
 * Defines an entity queue handler that manages an eyecatcher queue.
 *
 * @EntityQueueHandler(
 *   id = "eyecatcher",
 *   title = @Translation("Eyecatcher queue")
 * )
 */
class EyecatcherQueueHandler extends Simple {}
