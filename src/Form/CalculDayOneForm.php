<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class CalculDayOneForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_one';
  }

  /**
   * {@inheritdoc}
   */
  public function __construct() {

  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['datas'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Copier / coller Datas'),
    );
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Lancer le calcul'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    //init array
    $datas = $form_state->getValue('datas');
    $arrayDatas = preg_split('/\n|\r\n?/', $datas);
    $intDatas = array_map('intval', $arrayDatas);
    $intSortedData = asort($intDatas);
    $countDatas = count($intDatas);


    $batch = array(
      'title' => t('Lancement du calcul...'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => CalculDayOneForm::class . '::batchFinished',
      'operations' => [
        [CalculDayOneForm::class . '::batchProcess', [$intDatas]],
      ]
    );

    batch_set($batch);
  }

  public static function batchProcess($datas, &$context) {
    set_time_limit(300); // 5min


    if (empty($context['sandbox'])) {
      $context['sandbox'] = [];
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = 2;

      //init array
      $intDatas = array_map('intval', explode(',', $string));
    }


    $limit = $context['sandbox']['progress'] + 1;
    if ($limit > $context['sandbox']['max']) {
      $limit = $context['sandbox']['max'];
    }

    while($context['sandbox']['progress'] < $limit) {

      //traitement ici à découper en segments...
      foreach ($datas as $data1){
        foreach ($datas as $data2){
          foreach ($datas as $data3) {

            if (((int)$data1 + (int)$data2 + (int)$data3) == 2020) {
              $result = $data1 * $data2 * $data3;
              $context['finished'] = 1;
              $context['sandbox']['progress'] = $context['sandbox']['max'];
              $context['message']['result'] = "Trouvé ! : ".$result;
              $context['results'][] = "$data1 * $data2 * $data3 = $result";
              continue(3);
            }
          }
        }
      }

      $context['sandbox']['progress']++;
    }

    // Inform the batch engine that we are not finished,
    // and provide an estimation of the completion level we reached.
    if ($context['sandbox']['progress'] !== $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] >= $context['sandbox']['max'];
      $context['message'] = $context['sandbox']['progress'] . '/' . $context['sandbox']['max'];
    }
  }

  public static function batchFinished($success, $results, $operations) {
    $messenger = \Drupal::messenger();
    if ($success) {
      $messenger
        ->addMessage(t('resultat : @result', [
          '@result' => $results[0],
        ]));
    }
    else {
      $error_operation = reset($operations);
      $messenger
        ->addMessage(t('An error occurred while processing @operation with arguments : @args', [
          '@operation' => $error_operation[0],
          '@args' => print_r($error_operation[0], TRUE),
        ]));
    }
  }

}
