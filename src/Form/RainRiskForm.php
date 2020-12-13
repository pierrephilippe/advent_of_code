<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class RainRiskForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_twelve';
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

    //step 1
    $position['x'] = 0;
    $position['y'] = 0;
    $direction = 1;

    foreach ($arrayDatas as $key => $value) {

      //Directions :
      // 1 => E : +X
      // 2 => S :-Y
      // 3 => W : -X
      // 4 => N : +Y
      if(in_array($value, ['R90', 'L270'])) {
        $direction += 1;
      } else if (in_array($value, ['R180', 'L180'])) {
        $direction += 2;
      } else if (in_array($value, ['R270', 'L90'])) {
        $direction += 3;
      }

      if($direction > 4){
        $direction -= 4;
      }

      $instruction = $value[0];
      $value = (int) substr($value, 1);

      switch ($instruction) {
        case "F" :
          switch ($direction) {
            case 1: $position['x'] += $value; break;
            case 2: $position['y'] -= $value; break;
            case 3: $position['x'] -= $value; break;
            case 4: $position['y'] += $value; break;
            default: break;
          }
          break;
        case "E" : $position['x'] += $value; break;
        case "W" : $position['x'] -= $value; break;
        case "N" : $position['y'] += $value; break;
        case "S" : $position['y'] -= $value; break;
      }

      $result = abs($position['x']) + abs($position['y']);

    }

    //step 2
    $position['x'] = 0;
    $position['y'] = 0;
    $waypoint['x'] = 10;
    $waypoint['y'] = 1;
    $direction = 1;

    foreach ($arrayDatas as $key => $value) {

      //Directions :
      // 1 => E : +X
      // 2 => S :-Y
      // 3 => W : -X
      // 4 => N : +Y
      if(in_array($value, ['R90', 'L270'])) {
        $direction += 1;
        $waypointKeys = array_keys($waypoint);
        $waypointValues = array_values($waypoint);
        $waypointValues = array_reverse($waypointValues);
        $waypoint = array_combine($waypointKeys, $waypointValues);
        $waypoint['y'] = -$waypoint['y'];
      } else if (in_array($value, ['R180', 'L180'])) {
        $direction += 2;
        $waypoint['x'] = -$waypoint['x'];
        $waypoint['y'] = -$waypoint['y'];
      } else if (in_array($value, ['R270', 'L90'])) {
        $direction += 3;
        $waypointKeys = array_keys($waypoint);
        $waypointValues = array_values($waypoint);
        $waypointValues = array_reverse($waypointValues);
        $waypoint = array_combine($waypointKeys, $waypointValues);
        $waypoint['x'] = -$waypoint['x'];
      }

      if($direction > 4){
        $direction -= 4;
      }

      $instruction = $value[0];
      $value = (int) substr($value, 1);

      switch ($instruction) {
        case "F" :
          $position['x'] += $waypoint['x'] * $value;
          $position['y'] += $waypoint['y'] * $value;
          break;
        case "E" : $waypoint['x'] += $value; break;
        case "W" : $waypoint['x'] -= $value; break;
        case "N" : $waypoint['y'] += $value; break;
        case "S" : $waypoint['y'] -= $value; break;
      }
    }
    $result2 = abs($position['x']) + abs($position['y']);


    $this->messenger()->addStatus($this->t('Result = @result, | @result2',
      ['@result' => $result,
       '@result2' => $result2,
        ]));
  }
}
