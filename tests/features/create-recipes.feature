@recipes
Feature: Create new the Recipe
    In order to create new recipes
    An autheticated user
    I need see the new recipe created

    Scenario: I want to create new recipe
        Given I am an autheticated user
        When I submit the new recipe
        Then I get 201 and see the new recipe data
        And I see the new recipe in recipes first page
