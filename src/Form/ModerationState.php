<?php

namespace Drupal\ogdp_moderation_state\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Component\Serialization\Json;
use Drupal\node\Entity\Node;

/**
 * Provides a form for deleting a batch_import_example entity.
 *
 * @ingroup batch_import_example
 */
class ModerationState extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() : string {
    return 'batch_apply_moderation_state';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['#prefix'] = '<p>This example form will apply moderation states from the file/content-catalog.csv </p>';

    $form['actions'] = array(
      '#type' => 'actions',
      'submit' => array(
        '#type' => 'submit',
        '#value' => 'Proceed',
      ),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

	
	$item = array();
    $item[0]['nid'] = "6750323";
    $item[1]['nid'] = "6750323";
    $item[2]['nid'] = "6750323";
    $item[3]['nid'] = "6750323";
    $item[4]['nid'] = "6750323";
    $item[5]['nid'] = "6750323";
	
	
    $item[0]['state'] = "draft";
    $item[1]['state'] = "pmu";
    $item[2]['state'] = "cdo";
    $item[3]['state'] = "pmu";
    $item[4]['state'] = "cdo";
    $item[5]['state'] = "pmu";
	
	
    $item[0]['user'] = "464901";
    $item[1]['user'] = "464901";
    $item[2]['user'] = "89";
    $item[3]['user'] = "464901";
    $item[4]['user'] = "89";
    $item[5]['user'] = "464901";

    $batch = [
    'title' => t('Applying Moderation states'),
    'operations' => [],
    'init_message' => t('starting.'),
    'progress_message' => t('Processed @current out of @total. Estimated time: @estimate.'),
    'error_message' => t('The process has encountered an error.'),
    ];

    foreach($item as $data) {
      $batch['operations'][] = [['\Drupal\ogdp_moderation_state\Form\ModerationState', 'change_moderation_state'], [$data]];
    }

    batch_set($batch);
    \Drupal::messenger()->addMessage('Applied on ' . $total . ' nodes!');

    $form_state->setRebuild(TRUE);
  }

  /**
   * @param $entity
   * Deletes an entity
   */
  public function change_moderation_state($data, &$context) {
    \Drupal::logger('nids')->notice($data['nid']);
	$node = Node::load($nid);
	$node->set('moderation_state', $data['state']);
    $node->save();
    $context['results'][] = $data['nid'];
    $context['message'] = t('Created @title', array('@title' => $item['name']));
  }

}
