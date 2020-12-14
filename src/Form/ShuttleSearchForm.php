<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class ShuttleSearchForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_thirteen';
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


    $earlierTimestamp = (int) $arrayDatas[0];
    $allBus = $arrayDatas[1];
    $arrayAllBus = preg_split('/,/', $allBus);

    $firstBus = ((int) $arrayAllBus[0]);
    $minusQuotient = (int) ($earlierTimestamp / $firstBus);
    $minusModulo = $earlierTimestamp % $firstBus;
    $ecart = $earlierTimestamp;

    $busStep2 = [];


    foreach ($arrayAllBus as $key => $bus) {
      if($bus != 'x'){
        $bus = (int) $bus;
        $quotient = (int) ($earlierTimestamp / $bus);
        $somme = $bus * $quotient + $bus;
        if(($somme - $earlierTimestamp) > 0 && ($somme - $earlierTimestamp) < $ecart){
          $modulo = $earlierTimestamp % $bus;
          $ecart = $somme - $earlierTimestamp;
          $minusQuotient = $quotient;
          $minusModulo = $modulo;
          $firstBus = $bus;
        }
      }
    }


    //step2
    $pas = (int) $arrayAllBus[0];
    $timestamp = $pas;

      foreach ($arrayAllBus as $key => $bus) {
        if($bus != 'x') {
          $bus = (int)$bus;
          if ($key > 0) {
            while (($timestamp + $key) % $bus != 0) {
              $timestamp += $pas;
            }
            $pas *= $bus;
          }
        }
      }



    echo $pas;
    //944 - 939 = 5
    //5 * 59
    $result = $ecart * $firstBus;

    $this->messenger()->addStatus($this->t('Retour : @retour, | timestamp : @timestamp',
      ['@retour' => $result,
        '@timestamp' => $timestamp]
    ));
  }
}
