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
    And I resize window "1400" by "1200"

  @javascript
  Scenario: Fill out Contact Form
    Given I go to "/about-us/contact"
    Then I should see "Contact"
    And I should see "Name"
    And I should see "Email"
    And I should see "Phone"
    And I should see "Message"
    And I take a screenshot with name "contactform_load"
    
    When fill in the following:
      | Name | Mariah Carey |
      | Email | test_example_com |
      | Phone | 04 1234567 |
      | Message | All I want for Christmas is you. |
    And I click the element "#Form_Form_action_doForm"
    # Difficult to check for validation here, because the browser does its own html5 validation on fields
    # so we can instead check that the success message does not appear
    Then I should not see "Thanks for your submission. We'll be in touch soon."
    And I take a screenshot with name "contactform_populated_invalid_email"

    When fill in "Email" with "test@example.com"
    And I take a screenshot with name "contactform_populated"
    And I click the element "#Form_Form_action_doForm"
    Then I should see "Thanks for your submission. We'll be in touch soon."
    And I take a screenshot with name "contactform_submitted"
 