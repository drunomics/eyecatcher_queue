<?php

namespace Drupal\eyecatcher_queue\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Url;
use Drupal\eyecatcher_queue\Service\EyecatcherServiceTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Eyecatcher Config Controller.
 */
class EyecatcherConfigController extends ControllerBase {

  use EyecatcherServiceTrait;

  /**
   * Path stack.
   *
   * @var CurrentPathStack
   */
  protected $pathStack;

  /**
   * EyecatcherConfigController constructor.
   *
   * @param CurrentPathStack $pathStack
   *   Path stack.
   */
  public function __construct(CurrentPathStack $pathStack) {
    $this->pathStack = $pathStack;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('path.current'));
  }

  /**
   * Eyecatcher config page for a specific queue.
   *
   * Since the config is actually an entity subqueue, we redirect to the
   * subqueue configuration.
   *
   * @return RedirectResponse
   */
  public function eyecatcherQueueConfig() {
    $queue = preg_replace('/.*\//', '', $this->pathStack->getPath());
    return new RedirectResponse(sprintf('/admin/structure/entityqueue/%1$s/%1$s', $queue));
  }

  /**
   * Checks access for the overview.
   *
   * @param AccountInterface $account
   *   Run access checks for this account.
   *
   * @return AccessResult
   */
  public function overviewAccess(AccountInterface $account) {
    foreach ($this->getEyecatcherQueueService()->getEyecatcherSubqueues() as $subqueue) {
      if ($subqueue->access('update')) {
        return AccessResult::allowed();
      }
    }

    return AccessResult::forbidden();
  }

  /**
   * Eyecatcher Overview.
   *
   * @return array
   */
  public function eyecatcherOverview() {
    $build = [
      '#theme' => 'admin_block_content',
    ];
    $content = [];

    foreach ($this->getEyecatcherQueueService()->getEyecatcherSubqueues() as $subqueue) {
      if (!$subqueue->access('update')) {
        continue;
      }
      $queue = $subqueue->getQueue();
      $content[$queue->id()] = [
        'title' => $queue->label(),
        'options' => [],
        'url' => Url::fromRoute('eyecatcher_queue.eyecatcher.config.' . $queue->id()),
      ];
    }

    $build['#content'] = $content;

    return $build;
  }

}
