<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class SeatingSystemForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_eleven';
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

    //STEP 1
    $arrayBefore = $arrayDatas;


    $end = false;
    while(!$end){
      $arrayAfter = [];
      foreach ($arrayBefore as $posX => $line){
        $line = str_split(trim($line));
        $arrayAfter[$posX] = "";

        foreach ($line as $posY => $place){
          switch($place) {
            case 'L':
              //check no '#' around => '#'
              $place = (SeatingSystemForm::findAround_v2(0, $posX, $posY, $arrayBefore)) ? '#' : $place;
              break;
            case '#':
              //check 4+  '#' around => 'L'
              $place = (SeatingSystemForm::findAround_v2( 5, $posX, $posY, $arrayBefore)) ? 'L' : $place;
              break;
            default: break;
          }

          $arrayAfter[$posX] .= $place;
        }
      }
      // A bougé ?
      $arrayDiff = array_diff($arrayBefore, $arrayAfter);
      if(empty($arrayDiff)){
        $end = true;
      } else {
        $arrayBefore = $arrayAfter;
      }
    }

    //count '#'
    $chaine = trim(implode('', $arrayAfter));
    $occurence = substr_count($chaine,'#');


    $this->messenger()->addStatus($this->t('occurence, @occurence',
      ['@occurence' => $occurence,
      ]));
  }

  static function findAround($qte, $posX, $posY, $datas) {

    $occuped = 0;

    for($i = $posX - 1; $i <= $posX + 1; $i++){
      if(isset($datas[$i])){
        $datas[$i] = str_split($datas[$i]);
        for($j = $posY - 1; $j <= $posY + 1; $j++){
          if(isset($datas[$i][$j]) && !($i == $posX && $j == $posY)){
            $occuped += ($datas[$i][$j] == '#') ? 1 : 0;
          }
        }
      }
    }

    if($qte == 0){
      return ($occuped == 0) ? true : false;
    } elseif ($qte > 0){
      return ($occuped >= $qte) ? true : false;
    } else {
      return false;
    }
  }

  static function findAround_v2($qte, $posX, $posY, $datas) {

    $occuped = 0;
    $xLength = count($datas);

    $search = [];
    //toLeft

    $search['toLeft'] = ($posY > 0) ? strrev(substr($datas[$posX], 0, $posY)) : '';
    $search['toRight'] =($posY < strlen($datas[$posX]) && substr($datas[$posX],$posY+1)) ? substr($datas[$posX],$posY+1) : '';

    if($posX > 0 ){
      $search['toLeftTop'] = $search['toTop'] = $search['toRightTop'] = '';
      $distance=1;
      for($i = $posX - 1; $i >= 0; $i--){
        $search['toTop'] .= (substr($datas[$i],$posY,1)) ? substr($datas[$i],$posY,1) : '';
        $search['toLeftTop'] .= ($posY-$distance >= 0 && substr($datas[$i],$posY-$distance,1)) ? substr($datas[$i],$posY-$distance,1) : '';
        $search['toRightTop'] .= (substr($datas[$i],$posY+$distance,1)) ? substr($datas[$i],$posY+$distance,1) : '';
        $distance++;
      }
    }

    if($posX < $xLength){
      $search['toLeftBottom'] = $search['toBottom'] = $search['toRightBottom'] = '';
      $distance=1;
      for($i = $posX + 1; $i < $xLength; $i++){
        if(!isset($datas[$i])){
          echo $i;
          die();
        }
        $search['toBottom'] .= (substr($datas[$i],$posY,1)) ? substr($datas[$i],$posY,1): '';
        $search['toLeftBottom'] .= ($posY-$distance >= 0 && (substr($datas[$i],$posY-$distance,1))) ? substr($datas[$i],$posY-$distance,1) : '';
        $search['toRightBottom'] .= (substr($datas[$i],$posY+$distance,1)) ? substr($datas[$i],$posY+$distance,1) : '';
        $distance++;
      }
    }

    foreach($search as $string){
      //on vire les place vides
      $string = str_replace('.','',$string);
      if(substr($string,0,1) == '#'){
        $occuped++;
      }
    }



    if($qte == 0){
      return ($occuped == 0) ? true : false;
    } elseif ($qte > 0){
      return ($occuped >= $qte) ? true : false;
    } else {
      return false;
    }
  }
}
