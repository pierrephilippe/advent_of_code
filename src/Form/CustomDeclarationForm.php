<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class CustomDeclarationForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_six';
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
    $arrayDatas = preg_split('/[\r\n]{3}/', $datas);



    $total = 0;
    foreach ($arrayDatas as $line){
      $string = str_replace(["\n","\r"],'',$line);
      $lettres = array_unique(str_split($string));
      $total += count($lettres);
    }

    $total2 = 0;
    foreach ($arrayDatas as $line){
      $strings = explode("\r\n",$line);
      if(count($strings) == 1){
        $string = str_replace(["\n","\r"],'',$strings[0]);
        $total2 += count(array_unique(str_split($string)));
      } else {
        foreach($strings as $key => $string){
          $string = str_replace(["\n","\r"],'',$string);
          $strings[$key] = str_split($string);
        }
        $total2 += count(call_user_func_array('array_intersect',$strings));
      }

    }


    $this->messenger()->addStatus($this->t('Total : @total, total2 : @total2',
      ['@total' => $total,
        '@total2' => $total2]));
  }
}
