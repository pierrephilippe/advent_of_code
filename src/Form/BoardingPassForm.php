<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class BoardingPassForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_five';
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

    //0 - 127
    //    Start by considering the whole range, rows 0 through 127.
    //    F means to take the lower half, keeping rows 0 through 63. (floor)
    //    B means to take the upper half, keeping rows 32 through 63. (ceil)
    //    F means to take the lower half, keeping rows 32 through 47.
    //    B means to take the upper half, keeping rows 40 through 47.

    //    Start by considering the whole range, columns 0 through 7.
    //    R means to take the upper half, keeping columns 4 through 7.
    //    L means to take the lower half, keeping columns 4 through 5.
    //    The final R keeps the upper of the two, column 5.

    $results = [];
    foreach ($arrayDatas as $line){
      $place['x'] = [0,7];
      $place['y'] = [0,127];
      $row = $col = 0;
      for($position = 0; $position < 7; $position++) {
        if($line[$position] == 'F'){
          $row = $place['y'][1] -= ceil(($place['y'][1] - $place['y'][0]) / 2);
        } else {
          $row = $place['y'][0] += ceil(($place['y'][1] - $place['y'][0]) / 2);
        }
      }
      for($position = 7; $position < 10; $position++) {
        if($line[$position] == 'L'){
          $col = $place['x'][1] -= ceil(($place['x'][1] - $place['x'][0]) / 2);
        } else {
          $col = $place['x'][0] += ceil(($place['x'][1] - $place['x'][0]) / 2);
        }
      }
      $results[] = intval($row * 8 + $col);
    }

    sort($results);

    foreach($results as $key => $result){
      if(isset($results[$key]) && isset($results[$key+1]) && $results[$key +1] != $results[$key] + 1){
        $myseatid = $results[$key +1];
        break;
      }
    }

    $this->messenger()->addStatus($this->t('Max : @max, my place : @myplace',
      ['@max' => max($results),
        '@myplace' => $myseatid]));
  }
}
