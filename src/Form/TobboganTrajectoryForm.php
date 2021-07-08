<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class TobboganTrajectoryForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_three';
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



    $move[0]['x'] = 1;
    $move[0]['y'] = 1;
    $move[1]['x'] = 3;
    $move[1]['y'] = 1;
    $move[2]['x'] = 5;
    $move[2]['y'] = 1;
    $move[3]['x'] = 7;
    $move[3]['y'] = 1;
    $move[4]['x'] = 1;
    $move[4]['y'] = 2;
    for($i=0; $i<=4; $i++){
      $cpt[$i] = $this->countHashtag($move[$i], $arrayDatas);
    }

    $retour = $cpt[0]['Hashtag']*$cpt[1]['Hashtag']*$cpt[2]['Hashtag']*$cpt[3]['Hashtag']*$cpt[4]['Hashtag'];



    $this->messenger()->addStatus($this->t('Nb de sapins : @number',
      ['@number' => $retour]));
  }

  private function countHashtag($move, $arrayDatas){
    $cpt['Hashtag'] = 0;
    $cpt['Point'] = 0;

    $position['x'] = 0;
    $position['y'] = 0;

    $height = count($arrayDatas);
    $length = strlen($arrayDatas[0]);

    $end = false;

    while(!$end){

      $position['x'] = $position['x'] + $move['x'];
      if($position['x'] >= $length){
        $position['x'] = $position['x'] - ($length);
      }
      $position['y'] = $position['y'] + $move['y'];
      if($position['y'] >= $height){
        $end = true;
      } else {
        if($arrayDatas[$position['y']][$position['x']] == '#'){
          $cpt['Hashtag'] = $cpt['Hashtag'] + 1;
          $arrayDatas[$position['y']][$position['x']] = "X";
        } else {
          $cpt['Point'] = $cpt['Point'] + 1;
          $arrayDatas[$position['y']][$position['x']] = "0";
        }
      }
    }

    return $cpt;
  }
}
