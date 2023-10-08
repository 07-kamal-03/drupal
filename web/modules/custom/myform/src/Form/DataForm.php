<?php
namespace Drupal\myform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class DataForm extends FormBase
{

  public function getFormId()
  {
    return 'myform';
  }

  public function buildForm(array $form, FormStateInterface $form_state)
  {

    $form['firstname'] = [
      '#type' => 'textfield',
      '#title' => t('First Name'),
      '#required' => TRUE,
    ];

    $form['lastname'] = [
      '#type' => 'textfield',
      '#title' => t('Last Name'),
      '#required' => TRUE,
    ];


    $form['email'] = [
      '#type' => 'email',
      '#title' => t('Email'),
      '#required' => TRUE,
    ];

    $options_gender = [
      'Male' => t('Male'),
      'Female' => t('Female'),
      'Other' => t('Other'),
    ];

    $form['gender'] = [
      '#type' => 'radios',
      '#title' => t('Gender'),
      '#options' => $options_gender,
      '#default_value' => '',
    ];

    $options_interest = [
      'Computer' => t('Computer'),
      'Electrical' => t('Electrical'),
      'Mechanical' => t('Mechanical'),
    ];

    $form['interest'] = [
      '#type' => 'checkboxes',
      '#title' => t('Area of Interest'),
      '#options' => $options_interest,
      '#default_value' => [],
    ];
    $opt=static::country();
    if(empty($form_state->getValue('country')))
    {
      $value='none';
    }
    else{
      $value=$form_state->getValue('country');
    }
    $form['country'] = [
      '#type' => 'select',
      '#title' => t('Country'),
      '#options' => $opt,
      '#default_option' => '',
      '#ajax' => [
        'callback' => [$this, 'getLocationCallback'],
        'wrapper' => 'location-output',
        'event' => 'change',
      ],
    ];



    $form['states'] = [
      '#type' => 'select',
      '#title' => t('State'),
      '#options' => static::states($value),
      '#default_option'=>!empty( $form_state->getValue('state'))? $form_state->getValue('country'):'',
      '#prefix' => '<div id="location-output">',
      '#suffix' => '</div>',
    ];

    $form['address'] = [
      '#type' => 'textarea',
      '#title' => t('Address'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#type' => 'submit',
      '#value' => t('Submit'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $firstname = $form_state->getValue('firstname');
    $lastname = $form_state->getValue('lastname');
    $email = $form_state->getValue('email');
    $gender = $form_state->getValue('gender');
    $interest = array_filter($form_state->getValue('interest'));
    $location = $form_state->getValue('location');
    $address = $form_state->getValue('address');

    $checked_interest = implode(',', $interest);

    \Drupal::database()->insert('myform_data')->fields([
      'firstname' => $firstname,
      'lastname' => $lastname,
      'email' => $email,
      'gender' => $gender,
      'interest' => $checked_interest,
      'location' => $location,
      'address' => $address,
    ])->execute();

    $this->messenger()->addStatus($this->t('Thank you, @name Your Form has been submitted successfully.', ['@name' => $firstname]));
  }

  public function getLocationCallback(array &$form, FormStateInterface $form_state)
  {
        return $form['states'];
  }

  public function country(){
    return [
      'none' => 'none',
      'India' => 'India',
      'America' => 'America',
      'England' => 'England',
    ];
  }

  public function states($value)
  {
    switch($value){
      case 'India':
        $opt =[
          'Tamilnadu' => 'Tamilnadu',
          'Kerala' => 'Kerala',
          'Karnataka' => 'Karnataka',
        ];
        break;
      case 'America':
        $opt =[
          'Texas' => 'Losangel',
          'California' => 'California',
          'Florida' => 'Florida',
        ];
        break;
      case 'UK':
        $opt =[
          'England' => 'England',
          'Scotland' => 'Scotland',
          'Wales' => 'Wales',
        ];
        break;
        default:
        $opt =['none'=>'none'];
    }
    return $opt;
  }


}
?>