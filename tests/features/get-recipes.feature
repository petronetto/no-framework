@recipes
Feature: Get the Recipes
    In order to get the recipes
    An anonymous user
    I need see the recipes results paginated

    Scenario: I want to get the list of recipes
        Given I am an anonymous user
        When I request the recipes endpoint
        Then I get the results paginated
        And I request the recipes second page
        Then I seed the results for the second page of the recipes

    Scenario: I want to get just one recipe by Id
        Given I am an anonymous user
        When I request the recipes endpoint passing the recipe Id
        Then I get the data for that recipe

    Scenario: I want to set a different page size
        Given I am an anonymous user
        When I request the recipes endpoint passing the page size
        Then I get the data for that recipe with a different page size
