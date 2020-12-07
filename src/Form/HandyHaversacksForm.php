<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class HandyHaversacksForm extends FormBase {

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

    //    "light red" < 1 "bright white", 2 "muted yellow"      : LR < BW + 2MY
    //    "dark orange" < 3 "bright white ", 4 "muted yellow"   : DO < 3BW + 4MY
    //    "bright white" < 1 "shiny gold"                       : BW < 1SG
    //    "muted yellow" < 2 "shiny gold", 9 "faded blue"       : MY < 2 SG + 9FB
    //    "shiny gold" < 1 "dark olive" 2 "vibrant plum"        : SG <DO + 2 VP
    //    "dark olive" < 3 "faded blue", 4 "dotted black"       : DO < 3FB + 4 DB
    //    "vibrant plum" < 5 "faded blue", 6 "dotted black"     : VP < 5FB + 6DB
    //    "faded blue" < no                                     : FB < 0
    //    "dotted black" < no                                   : DB < 0

    // SG => BW
    // SG => BW => LR
    // SG => BW => LR ...
    // SG => BW => DO => SG
    // SG => MY
    // SG => MY => LR
    // SG => MY => DO


    // SG 3 (1+2)
    // LR 3 (1+2)
    // DO 7 (3+4)
    // MY 11 (2+9)

    $results = [];

    //FORMAT $results["name bag"] < ["name bag"][compteur]
    foreach ($arrayDatas as $line){
      list($container, $contains) = explode('contain', $line);
      $container = trim(str_replace(" bags", "", $container));
      $containsElements = explode(',', $contains);
      foreach($containsElements as $element){
        $bag = str_replace([' bags.',' bag.',' bags',' bag'], "", $element);
        $bagArray = explode(' ', $bag);
        $number = $bagArray[1];
        unset($bagArray[0]);
        unset($bagArray[1]);
        $name = trim(implode(' ', $bagArray));
        $results[$container][$name] = $number;
      }
    }

    $search = "shiny gold";
    $found = HandyHaversacksForm::find_in_array($search, $results, []);
    array_unique($found);


    //2
    $poids = HandyHaversacksForm::get_bags_inside($search, $results, 0);





    $this->messenger()->addStatus($this->t('Compteur : @compteur, poids : @poids',
      ['@compteur' => count(array_unique($found)),
        '@poids' => $poids]));
  }

  static function find_in_array($search, $array, $found)
  {
    foreach ($array as $key => $subarray) {
      if (array_key_exists(trim($search), $subarray)){
        if(!in_array($key, $found)){
          $found[] = $key;
          $found = array_merge($found, HandyHaversacksForm::find_in_array($key, $array, $found));
        }
      }
    }
    return array_unique($found);
  }

  static function get_bags_inside($search, $array, $poids){
    $poids = 0;
    if(isset($array[$search])){
      foreach($array[$search] as $name => $value){
        if($value != "no") {
          $poids += (int)$value + ((int)$value * HandyHaversacksForm::get_bags_inside($name, $array, $poids));
        } else {
          return 0;
        }
      }


    }
    return $poids;
  }
}


