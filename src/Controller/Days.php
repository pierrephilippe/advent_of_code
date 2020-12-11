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

  /**
   * Returns a render-able array for a test page.
   */
  public function day4() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\CheckPassportForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day5() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\BoardingPassForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day6() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\CustomDeclarationForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day7() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\HandyHaversacksForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day8() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\HandheldHaltingForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day9() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\EncodingErrorForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day10() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\AdapterArrayForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }

  /**
   * Returns a render-able array for a test page.
   */
  public function day11() {

    $form = \Drupal::formBuilder()->getForm('\Drupal\advent_of_code\Form\SeatingSystemForm');

    $build = [
      '#type' => 'markup',
      '#markup' => $form
    ];
    return $form;
  }
}
