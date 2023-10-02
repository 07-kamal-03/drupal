<?php
namespace Drupal\myform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class DataForm extends FormBase{

    public function getFormId()
    {
        return 'myform';
    }

    public function buildForm(array $form, FormStateInterface $form_state): array
{
        
        $form['firstname']=[
            '#type' =>'textfield',
            '#title' => t('First Name'),
            '#required' => TRUE,
        ];

        $form['lastname']=[
            '#type' =>'textfield',
            '#title' => t('Last Name'),
            '#required' => TRUE,
        ];


        $form['email']=[
            '#type' =>'email',
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

          $form['location'] = [
            '#type' => 'select',
            '#title' => t('Location'),
            '#options' => [
              'Bangalore,Karnataka' => t('Bangalore'),
              'Chennai,TamilNadu' => t('Chennai'),
              'Coimbatore,TamilNadu' => t('Coimbatore'),
            ],
            '#empty_option' => t('- Select location -'),
          '#ajax' => [
            'callback' => [$this, 'getLocationCallback'],
            'wrapper' => 'location-output',
          ],
        ];

        $form['location_output'] = [
            '#markup' => '<div id="location-output"></div>',
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

    public function submitForm(array &$form,FormStateInterface $form_state) {
            $firstname = $form_state->getValue('firstname');
            $lastname = $form_state->getValue('lastname');
            $email = $form_state->getValue('email');
            $gender = $form_state->getValue('gender');
            $interest = array_filter($form_state->getValue('interest'));
            $location = $form_state->getValue('location');
            $address = $form_state->getValue('address');
            
            $checked_interest = implode(',',$interest);

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

    public function getLocationCallback(array &$form, FormStateInterface $form_state) {
        $location = $form_state->getValue('location');
    
        $response = new AjaxResponse();
        $response->addCommand(
          new HtmlCommand('#location-output', $location)
        );
    
        return $response;
      }
    

}
?>