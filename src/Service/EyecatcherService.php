<?php

namespace Drupal\eyecatcher_queue\Service;

use drunomics\ServiceUtils\Core\Entity\EntityTypeManagerTrait;
use Drupal\Core\Entity\EntityInterface;
use Drupal\entityqueue\Entity\EntityQueue;
use Drupal\entityqueue\Entity\EntitySubqueue;

/**
 * Service class for eyecatcher queue.
 */
class EyecatcherService {

  use EntityTypeManagerTrait;

  const EYECATCHER_HANDLER_PLUGIN = 'eyecatcher';

  /**
   * Checks whether an entity is an eyecatcher queue.
   *
   * @param EntityInterface $entity
   *   The entity to check.
   *
   * @return bool
   *   TRUE if the entity is an eyecatcher queue.
   */
  public function isEyecatcherQueue(EntityInterface $entity = NULL) {
    if ($entity instanceof EntitySubqueue) {
      return $entity->getQueue()->getHandler() == self::EYECATCHER_HANDLER_PLUGIN;
    }
    elseif ($entity instanceof EntityQueue) {
      return $entity->getHandler() == self::EYECATCHER_HANDLER_PLUGIN;
    }

    return FALSE;
  }

  /**
   * Get all bundles of entities which are a eyecatcher queue.
   *
   * @return string[]
   */
  public function getEyecatcherQueueBundles() {
    return array_keys($this->getEyecatcherQueues());
  }

  /**
   * Get all queues which are of type eyecatcher queue.
   *
   * @return EntityQueue[]
   */
  public function getEyecatcherQueues() {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->getEntityTypeManager()->getStorage('entity_queue');
    /** @var EntityQueue[] $entities */
    $entities = $storage->loadByProperties(['handler' => self::EYECATCHER_HANDLER_PLUGIN]);
    return $entities;
  }

  /**
   * Get all subqueues which belong to an eyecatcher queue.
   *
   * @return EntitySubqueue[]
   */
  public function getEyecatcherSubqueues() {
    $bundles = $this->getEyecatcherQueueBundles();
    if (empty($bundles)) {
      return [];
    }
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->getEntityTypeManager()->getStorage('entity_subqueue');
    /** @var EntitySubqueue[] $entities */
    $entities = $storage->loadByProperties(['queue' => $bundles]);
    return $entities;
  }

  /**
   * Load a subqueue.
   *
   * @param int $id
   *   Entity id.
   *
   * @return EntitySubqueue|null
   */
  public function loadSubqueue($id) {
    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->getEntityTypeManager()->getStorage('entity_subqueue');
    /** @var EntitySubqueue $subqueue */
    $subqueue = $storage->load($id);
    return $subqueue;
  }

}
