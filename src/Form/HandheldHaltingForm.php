<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class HandheldHaltingForm extends FormBase {

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

    $alreadyVisited = [];
    $continue = true;
    $position = 0;
    $accumulator = 0;
    $compteur_change_instruction = 0;
    $positions_jmp_nom = [];
    foreach ($arrayDatas as $key => $line) {
      list($action, $commande) = explode(' ',$line);
      if(in_array($action,['jmp', 'nop'])){
        $positions_jmp_nom[] = $key;
      }
    }

    while($continue == true){
      if(in_array($position, $alreadyVisited)){
        //reinit:
        $position = 0;
        $accumulator = 0;
        $alreadyVisited = [];
        $arrayDatas = preg_split('/\n|\r\n?/', $datas);

        //change instruction
        //reperer les nop et jpm
        //remplacer occurence $compteur_change_instruction
        $to_replace = $positions_jmp_nom[$compteur_change_instruction];
        $compteur_change_instruction++;
        list($action, $commande) = explode(' ', $arrayDatas[$to_replace]);
        $new_action = ($action == "jmp") ? "nop" : "jmp";
        $arrayDatas[$to_replace]= implode(' ',[$new_action, $commande]);

      } else {
        $alreadyVisited[] = $position;
      }

      if($position >= count($arrayDatas)){
        //fin
        $continue = false;
        break;
      }
      list($action, $commande) = explode(' ', $arrayDatas[$position]);
      $value['direction'] = substr($commande, 0, 1);
      $value['number'] = (int) substr($commande, 1);


      switch($action) {
        case 'acc' :
          $accumulator += ($value['direction'] == "+") ? $value['number'] : -$value['number'];
          $position++;
          break;
        case 'jmp' :
          $position += ($value['direction'] == "+") ? $value['number'] : -$value['number'];
          break;
        default:
          $position++;
          break;
      }
    }


    $this->messenger()->addStatus($this->t('Accumulator : @accumulator',
      ['@accumulator' => $accumulator]));
  }
}
