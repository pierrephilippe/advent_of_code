<?php

namespace Drupal\advent_of_code\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a engie_media form.
 */
class CheckPassportForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'advent_of_code_calcul_day_four';
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



    $batch = array(
      'title' => t('Lancement du calcul...'),
      'progress_message' => t('Processed @current out of @total.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => CheckPassportForm::class . '::batchFinished',
      'operations' => [
        [CheckPassportForm::class . '::batchProcess', [$arrayDatas]],
      ]
    );

    batch_set($batch);
  }

  public static function batchProcess($datas, &$context) {
    set_time_limit(300); // 5min
    $nb_a_traiter = 20;

    if (empty($context['sandbox'])) {
      $context['sandbox'] = [];
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['max'] = count($datas);
      $context['message'] = "Il y a " . count($datas) . " passports à traiter";
      $context['results']['valid'] = 0;
      $context['results']['invalid'] = 0;
    }

    $limit = $context['sandbox']['progress'] + $nb_a_traiter;
    if($limit >= $context['sandbox']['max']){
      $limit = $context['sandbox']['max'];
    }

    for($i = $context['sandbox']['progress']; $i < $limit; $i++) {
        $isValid = CheckPassportForm::checkPassport($datas[$i]);
        if ($isValid['status'] == "true") {
          $context['results']['valid']++;
        } else {
          $context['results']['invalid']++;
          $context['results']['errors'][] = $isValid['message'];
        }
    }
    $context['sandbox']['progress'] += $nb_a_traiter;


    // Inform the batch engine that we are not finished,
    // and provide an estimation of the completion level we reached.
    if ($context['sandbox']['progress'] !== $context['sandbox']['max']) {
      $context['finished'] = $context['sandbox']['progress'] >= $context['sandbox']['max'];
      $context['message'] = $context['sandbox']['progress'] . '/' . $context['sandbox']['max'];
    }
  }

  public static function batchFinished($success, $results, $operations) {
    $messenger = \Drupal::messenger();
    if ($success) {
      $messenger
        ->addMessage(t('nb valides :@valid', [
          '@valid' => $results['valid']
        ]))
        ->addMessage(t('nb invalides :@invalid', [
          '@invalid' => $results['invalid'],
        ]),'error');
      ;
      if(isset($results['errors'])){

        foreach ($results['errors'] as $key => $error){
          $messenger
            ->addMessage(t('erreur @nb : @error', [
              '@nb' => $key+1,
              '@error' => $error,
            ]),'error');
          ;
        }
      }
    }
    else {
      $error_operation = reset($operations);
      $messenger
        ->addMessage(t('An error occurred while processing @operation with arguments : @args', [
          '@operation' => $error_operation[0],
          '@args' => print_r($error_operation[0], TRUE),
        ]));
    }
  }

  static function checkPassport($passport){

    $required_fields = ['byr', 'iyr', 'eyr', 'hgt', 'hcl', 'ecl', 'pid'];
    $output['status'] = true;
    $output['message'] = "";

    //init array
    $elements = preg_split("/[\s,]+/", $passport);
    sort($elements);
    foreach ($elements as $key => $element){
      list($prefix, $value) = explode(':', $element);
      if (($key = array_search($prefix, $required_fields)) !== false) {
        unset($required_fields[$key]);
      }
      switch ($prefix){
        case 'byr' :
          if(strlen($value) != 4 || !preg_match('/^[0-9]*$/', $value) || (int)$value < 1920 || (int)$value > 2002) {
            $output['status'] = false;
            $output['message'] = $prefix . " < 1920 ou > 2002 : " . $value;
          }
          break;
        case 'iyr' :
          if(strlen($value) != 4 || !preg_match('/^[0-9]*$/', $value) || (int)$value < 2010 || (int)$value > 2020) {
            $output['status'] = false;
            $output['message'] = $prefix . " < 2010 ou > 2020 : " . $value;
          }
          break;
        case 'eyr' :
          if(strlen($value) != 4 || !preg_match('/^[0-9]*$/', $value) || (int)$value < 2020 || (int)$value > 2030) {
            $output['status'] = false;
            $output['message'] = $prefix . " < 2020 ou > 2030 : " . $value;
          }
          break;
        case 'hgt' :
          $numbers = preg_replace('/[^0-9]/', '', $value);
          $letters = preg_replace('/[^a-zA-Z]/', '', $value);
          if(!in_array($letters, ['cm', 'in'])) {
            $output['status'] = false;
            $output['message'] = $prefix . " not cm or in : " . $value;
          } elseif($value != (string)$numbers.$letters) {
            $output['status'] = false;
            $output['message'] = $prefix . " must formatted number + lettres " . $value;
          } elseif($letters == "cm" && ($numbers < 150 || $numbers > 193)) {
            $output['status'] = false;
            $output['message'] = $prefix . " < 150 or > 193 : " . $value;
          } elseif ($letters == "in" && ($numbers < 59 || $numbers > 76)) {
            $output['status'] = false;
            $output['message'] = $prefix . " < 59 or > 76 : " . $value;
          }
          break;
        case 'hcl' :
          if(!preg_match('/^#{1}[0-9a-f]{6}$/', $value)){
            $output['status'] = false;
            $output['message'] = $prefix . " not # + 6hexa : " . $value;
          }
          break;
        case 'ecl' :
          if(!in_array($value, ['amb','blu','brn','gry','grn','hzl','oth'])){
            $output['status'] = false;
            $output['message'] = $prefix . " not 'amb','blu','brn','gry','grn','hzl','oth' : " . $value;
          }
          break;
        case 'pid' :
          if(strlen($value) != 9 || !preg_match('/^[0-9]*$/', $value)){
            $output['status'] = false;
            $output['message'] = $prefix . " not 9 digit : " . $value;
          }
          break;
      }
      if ($output['status'] == false) {
        return $output;
      }
    }


    if(!empty($required_fields)) {
      $output['status'] = false;
      $output['message'] = 'missing required prefix ' . implode(',', $required_fields);
    } else {
      $output['status'] = true;
      $output['message'] = 'ok';
    }
    return $output;

  }

}
