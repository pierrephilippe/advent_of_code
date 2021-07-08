<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class PasswordPoliciesForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_two';
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

    $compteur_first = 0;
    $compteur_second = 0;

    foreach ($arrayDatas as $line){
      list($range, $letters, $password) = explode(' ', $line);
      list($min, $max) = explode('-', $range);
      list($letter) = explode(':', $letters);

      //first policy
      $pattern = '/' . $letter . '/';
      $occurences = preg_match_all($pattern,$password);
      if($occurences >= $min && $occurences <= $max){
        $compteur_first++;
      }

      //second policy
      if(isset($password[$min-1]) && isset($password[$max-1])){
        $first_test =  ($password[$min-1] == $letter && $password[$max-1] != $letter);
        $second_test = ($password[$min-1] != $letter && $password[$max-1] == $letter);
        if($first_test || $second_test){
          $compteur_second++;
        }
      }
    }



    $this->messenger()->addStatus($this->t('Nombre de mot de passe valide : regle1 : @number1, regle2 : @number2',
            ['@number1' => $compteur_first,
             '@number2' => $compteur_second]));
  }
}
