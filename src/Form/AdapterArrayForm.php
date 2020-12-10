<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class AdapterArrayForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_ten';
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

    $power = 0;
    $oneJoltDiff = 0;
    $threeJoltDiff = 0;

    sort($arrayDatas);
    $max = count($arrayDatas);

    foreach ($arrayDatas as $key => $value){
      $value = (int) $value;
      $diff = $value - $power;
      if($key < $max && $diff <= 3){
          $oneJoltDiff += ($diff == 1) ? 1 : 0;
          $threeJoltDiff += ($diff == 3) ? 1 : 0;
          $power += $diff;
        }
    }
    $power += 3;
    $threeJoltDiff ++;

    //step 2
    //on compte les occurences
    // 1 1 => 2 permutations possibles
    // 1 1 1 => 4 permutations possibles
    $output = "";
    $output .= $arrayDatas[0];
    $power = 0;
    for($i = 1; $i < count($arrayDatas); $i++){
      $diff = (int)$arrayDatas[$i] - (int)$arrayDatas[$i-1];
      $output .= $diff;
    }
    $output .= "3";

    $five = substr_count($output, "11111");
    $output = str_replace('11111', '', $output);
    $four = substr_count($output, "1111");
    $output = str_replace('1111', '', $output);
    $three = substr_count($output, "111");
    $output = str_replace('111', '', $output);
    $two = substr_count( $output, "11");

    $step2 = pow(14,$five) * pow(7, $four) * pow(4, $three) * pow(2, $two);
    $this->messenger()->addStatus($this->t('Power : @power, Result = @result, 1Jolt : @oneJoltDiff, 3Jolt : @threeJoltDiff, step2 @step2',
      ['@power' => $power,
        '@result' => $oneJoltDiff * $threeJoltDiff,
        '@oneJoltDiff' => $oneJoltDiff,
        '@threeJoltDiff' => $threeJoltDiff,
        '@step2' => $step2
        ]));
  }
}
