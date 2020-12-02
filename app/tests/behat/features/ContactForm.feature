Feature: Interact with ContactForm page
  In order to submit a message via the contact page
  As a user
  I want to interact with the contact form
  # Given - is more on setting up the environment of system under test (SUT)
  # When - is about executing the process related to SUT
  # Then - is more about assertions
  # And - an auxiliary which supports Give, When, and Then

  Background:
    Given I populate default records
    And I resize window "1400" by "1600"

  @javascript
  Scenario: Fill out Contact Form
    Given I go to "/about-us/contact"

    #  STEP 1
    Then I should see "Contact Us"
    And I should see "Name"
    And I should see "Email"
    And I should see "Phone"
    And I should see "Message"
    And I take a screenshot with name "contactform_load"
    # When I click the element ".button__text"