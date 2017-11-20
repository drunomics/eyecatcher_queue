<?php

namespace Drupal\eyecatcher_queue;

use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\eyecatcher_queue\Service\EyecatcherServiceTrait;
use Drupal\system\PathBasedBreadcrumbBuilder;

/**
 * Custom BreadcrumbBuilder.
 */
class EyecatcherConfigBreadcrumbBuilder extends PathBasedBreadcrumbBuilder {

  use EyecatcherServiceTrait;

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    /** @var \Drupal\Core\Routing\RouteMatch $route */
    $route = $route_match->getCurrentRouteMatch();

    if ($route->getRouteName() != 'entity.entity_subqueue.edit_form') {
      return FALSE;
    }

    /** @var \Drupal\entityqueue\Entity\EntitySubqueue $subqueue */
    $subqueue = $route->getParameter('entity_subqueue');
    return $this->getEyecatcherQueueService()->isEyecatcherQueue($subqueue);
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $this->context->setPathInfo('/admin/config/content/eyecatcher/config');
    /** @var \Drupal\Core\Breadcrumb\Breadcrumb $breadcrumb */
    $breadcrumb = parent::build($route_match);
    $breadcrumb->addCacheContexts(['url.path']);
    return $breadcrumb;
  }

}
