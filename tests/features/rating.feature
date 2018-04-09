@recipes
Feature: Rating a recipe
    In order to give a rating for a recipe
    An anonymous user
    I need see the recipe rating average

    Scenario: I want to rating a recipe
        Given I an anonymous user
        When I submit the rateing for the recipe
        Then I get 201 and see the new rating average for the recipe
        And I see the new recipe with new stats in recipes first page
