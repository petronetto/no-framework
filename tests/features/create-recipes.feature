@recipes
Feature: Create new the Recipe
    In order to create new recipes
    An autheticated user and an anonymous user
    I need see the new recipe created

    Scenario: I want to create new recipe
        Given I am an autheticated user
        When I submit the new recipe
        Then I get 201 and see the new recipe data
        And I see the new recipe in recipes first page

    Scenario: I want to create new recipe as an unautheticated user
        Given I am an unautheticated user
        When I submit the new recipe without JWT token
        Then I get 401 and see the error message
