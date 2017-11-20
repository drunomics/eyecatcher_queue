<?php

namespace Drupal\eyecatcher_queue\Plugin\views\area;

use drunomics\ServiceUtils\Core\Entity\EntityTypeManagerTrait;
use Drupal\Core\Form\FormStateInterface;
use Drupal\eyecatcher_queue\Service\EyecatcherServiceTrait;
use Drupal\views\Plugin\views\area\AreaPluginBase;

/**
 * Eyecatcher area handlers. Insert an eyecatcher inside of an area.
 *
 * @ingroup views_area_handlers
 *
 * @ViewsArea("eyecatcher_queue_eyecatcher")
 */
class EyecatcherArea extends AreaPluginBase {

  use EyecatcherServiceTrait;
  use EntityTypeManagerTrait;

  /**
   * {@inheritdoc}
   */
  protected function defineOptions() {
    $options = parent::defineOptions();
    $options['eyecatcher_queue'] = ['default' => ''];
    return $options;
  }

  /**
   * {@inheritdoc}
   */
  public function adminSummary() {
    if ($subqueue = $this->getSelectedQueue()) {
      return $subqueue->label();
    }
    return parent::adminSummary();
  }

  /**
   * {@inheritdoc}
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state) {
    parent::buildOptionsForm($form, $form_state);
    $options = ['' => $this->t('-Select-')];
    $subqueues = $this->getEyecatcherQueueService()->getEyecatcherSubqueues();
    foreach ($subqueues as $subqueue) {
      $options[$subqueue->id()] = $subqueue->label();
    }

    $form['eyecatcher_queue'] = [
      '#type' => 'select',
      '#title' => $this->t('Eyecatcher queue'),
      '#default_value' => $this->options['eyecatcher_queue'],
      '#description' => $this->t('The queue which provides the eyecatcher.'),
      '#options' => $options,
    ];
  }

  /**
   * {@inheritdoc}
   *
   * Implemented via post render hook.
   *
   * @see postRender()
   */
  public function render($empty = FALSE) {}

  /**
   * {@inheritdoc}
   *
   * This method is invoked by the post render hook.
   *
   * @see eyecatcher_queue_views_post_render()
   */
  public function postRender(&$output) {
    $subqueue = $this->getSelectedQueue();
    if (!$subqueue) {
      return;
    }

    $items = $subqueue->get('items')->getValue();
    if (empty($items)) {
      return;
    }

    $page = $this->view->getPager()->getCurrentPage();
    $index = $page % count($items);
    $item = $items[$index];

    $eyecatcher_type = $subqueue->getQueue()->getTargetEntityTypeId();
    $eyecatcher = $this->getEntityTypeManager()->getStorage($eyecatcher_type)->load($item['target_id']);

    if ($eyecatcher) {
      $builder = $this->getEntityTypeManager()->getViewBuilder($eyecatcher_type);
      $render_array = $builder->view($eyecatcher, 'full');
      switch ($this->areaType) {
        case 'footer':
          $output['#rows'][] = $render_array;
          break;

        case 'header':
          array_unshift($output['#rows'], $render_array);
          break;
      }
    }
  }

  /**
   * Gets the selected queue.
   *
   * @return \Drupal\entityqueue\Entity\EntitySubqueue|null
   */
  public function getSelectedQueue() {
    $subqueue_id = $this->options['eyecatcher_queue'];
    return $this->getEyecatcherQueueService()->loadSubqueue($subqueue_id);
  }

}
