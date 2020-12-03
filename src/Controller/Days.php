<?php
namespace Drupal\advent_of_code\Controller;

use Drupal\Core\Controller\ControllerBase;


/**
 * An example controller.
 */
class Days extends ControllerBase {


  /**
   * Returns a render-able array for a test page.
   */
  public function day1() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\CalculDayOneForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day2() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\PasswordPoliciesForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day3() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\TobboganTrajectoryForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

}
