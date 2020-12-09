<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class EncodingErrorForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_nine';
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

    $preambule = 25;
    $retour = 0;
    foreach ($arrayDatas as $key => $value) {
      //all values possibles in the X last results
      if($key < $preambule){
        continue(1);
      } else {
        //all possibles values
        $possibleValues = [];

        for($i = ($key - $preambule); $i < ($key - 1) ; $i++){
          for($j = $i +1 ; $j < $key; $j++) {
            $possibleValues[] = (int) $arrayDatas[$i] + (int) $arrayDatas[$j];
          }
        }

        if(!in_array((int)$value, $possibleValues)){
          $retour = (int) $value;
          break;
        }
      }
    }

    foreach ($arrayDatas as $key => $value) {
      $position = $key;
      $val=[];
      $addition = (int)$arrayDatas[$position];
      $val[] = $addition;
      while($addition < $retour) {
        $position++;
        $valMax = (int)$arrayDatas[$position];
        $addition += $valMax;
        $val[] = $valMax;

        if($addition == $retour){
          $total = min($val) + max($val);
          continue(2);
        }
      }
    }

    $this->messenger()->addStatus($this->t('Retour : @retour ;  total @total',
      ['@retour' => $retour,
        '@total' => $total]
    ));
  }
}
