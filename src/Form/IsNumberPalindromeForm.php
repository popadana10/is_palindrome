<?php

namespace Drupal\is_palindrome\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class IsNumberPalindromeForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'is_number_palindrome_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['parent_wrapper'] = [
      '#type' => 'container',
      '#attributes' => ['class' => ['container', 'mt-5', 'mb-5']],
    ];
    // Create a textfield to accept numbers or strings.
    $form['parent_wrapper']['input_value'] = [
      '#type' => 'textfield',
      '#title' => $this->t('<h3 style="color:indianred">Please Enter a number or string</h3>'),
      // '#required' => TRUE,
      '#attributes' => [
        'autocomplete' => 'off',
        'placeholder' => $this->t('Enter a number or string'),
        'class' => ['form-control', 'mb-5', 'text-center'],
      ],
    ];

    // Submit button with AJAX support.
    $form['parent_wrapper']['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Is Palindrome'),
      '#ajax' => [
        'callback' => '::ajaxSubmitCallback',
        'wrapper' => 'palindrome-result',  // The ID where the result will be rendered.
      ],
      '#attributes' => [
          'class' => ['btn', 'btn-primary'],
        ],
    ];

    // Reset button with JavaScript to clear both form and result.
    $form['parent_wrapper']['actions']['reset'] = [
      '#type' => 'button',
      '#value' => $this->t('Reset'),
      '#attributes' => [
        'onclick' => 'document.getElementById("is-number-palindrome-form").reset(); document.getElementById("palindrome-result").innerHTML = ""; return false;',
        'class' => ['btn', 'btn-secondary'],
      ],
    ];

    // A container to display the result.
    $form['parent_wrapper']['result'] = [
      '#type' => 'container',
      '#attributes' => ['id' => 'palindrome-result'],
      '#markup' => '',  // This will be updated via AJAX.
    ];

    return $form;
  }

  /**
   * AJAX callback to handle form submission.
   */
  public function ajaxSubmitCallback(array &$form, FormStateInterface $form_state) {
    $input_value = $form_state->getValue('input_value');
    if(!empty($input_value)) {
      if ($this->isPalindrome($input_value)) {
        $result = '<h2 style="color:green;">' . $this->t('The input "@input" is a palindrome.', ['@input' => $input_value]) . '</h2>';
      } else {
        $result = '<h2 style="color:red">' . $this->t('The input "@input" is not a palindrome.', ['@input' => $input_value]) . '</h2>';
      }
    }else {
      $result = '<h3 style="color:red">please enter a valid number or string</h3>';

    }

    // Render the result in the form's 'result' container.
    $form['parent_wrapper']['result']['#markup'] = $result;
    return $form['parent_wrapper']['result'];
  }

  /**
   * Helper function to check if input is a palindrome.
   */
  private function isPalindrome($input) {
    // Convert input to a string and normalize it by removing spaces.
    $cleaned_input = preg_replace('/\s+/', '', strtolower($input));

    // Reverse the string.
    $reversed_input = strrev($cleaned_input);

    // Check if the cleaned input is equal to the reversed version.
    return $cleaned_input === $reversed_input;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // This is handled via the AJAX callback.
  }
}
