<?php

namespace Drupal\eyecatcher_queue\Service;

/**
 * Allows setter injection and simple usage of the service.
 */
trait EyecatcherServiceTrait {

  /**
   * The service.
   *
   * @var EyecatcherService
   */
  private $service;

  /**
   * Sets the service.
   *
   * @param EyecatcherService $service
   *   The service.
   *
   * @return $this
   */
  public function setEyecatcherQueueService(EyecatcherService $service) {
    $this->service = $service;
    return $this;
  }

  /**
   * Gets the service.
   *
   * @return EyecatcherService $service
   */
  public function getEyecatcherQueueService() {
    if (empty($this->service)) {
      $this->service = \Drupal::service('eyecatcher_queue.service.eyecatcher');
    }
    return $this->service;
  }

}
