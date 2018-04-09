<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use GuzzleHttp\Client;

class FeatureContext implements Context
{
    /** @var Client */
    protected $http;

    protected $response;

    /** @var string */
    protected $token;

    /** @var string */
    protected $username;

    /** @var string */
    protected $password;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = $password;

        $this->http = new Client([
            'base_uri'    => 'http://hellofresh.nginx:8080/api/v1/',
            'http_errors' => false,
        ]);
    }

    /**
     * @return array
     */
    public function getResponseData()
    {
        return json_decode($this->response->getBody(), true);
    }

    /**
     * @param  integer $code
     * @return void
     */
    public function checkStatusCode(int $code)
    {
        $statusCode = $this->response->getStatusCode();
        if ($statusCode != $code) {
            throw new \Exception("We spected {$code} but got {$statusCode}.");
        }
    }

    /**
     * @Given I am an anonymous user
     */
    public function iAmAnAnonymousUser()
    {
        return true;
    }

    /**
     * @When I request the recipes endpoint
     */
    public function iRequestTheRecipesEndpoint()
    {
        $this->response = $this->http->get('recipes');
    }

    /**
     * @Then I get the results paginated
     */
    public function iGetTheResultsPaginated()
    {
        $this->checkStatusCode(206);

        $data = $this->getResponseData();

        if (!array_key_exists('data', $data)) {
            throw new \Exception('The key "data" is not present in response.');
        }

        if (!array_key_exists('name', $data['data'][0])) {
            throw new \Exception('The key "name" is not present in response.');
        }

        if (!array_key_exists('description', $data['data'][0])) {
            throw new \Exception('The key "description" is not present in response.');
        }

        if (!array_key_exists('average_rating', $data['data'][0])) {
            throw new \Exception('The key "average_rating" is not present in response.');
        }

        if (!array_key_exists('meta', $data)) {
            throw new \Exception('The key "meta" is not present in response.');
        }
    }

    /**
     * @Then I request the recipes second page
     */
    public function iRequestTheRecipesSecondPage()
    {
        $this->response = $this->http->get('recipes?page=2');
    }

    /**
     * @Then I seed the results for the second page of the recipes
     */
    public function iSeedTheResultsForTheSecondPageOfTheRecipes()
    {
        $this->checkStatusCode(206);

        $data = $this->getResponseData();

        if (!array_key_exists('data', $data)) {
            throw new \Exception('The key "data" is not present in response.');
        }

        if (!array_key_exists('name', $data['data'][0])) {
            throw new \Exception('The key "name" is not present in response.');
        }

        if (!array_key_exists('description', $data['data'][0])) {
            throw new \Exception('The key "description" is not present in response.');
        }

        if (!array_key_exists('average_rating', $data['data'][0])) {
            throw new \Exception('The key "average_rating" is not present in response.');
        }

        if (!array_key_exists('meta', $data)) {
            throw new \Exception('The key "meta" is not present in response.');
        }

        if ($data['meta']['pagination']['current_page'] != 2) {
            throw new \Exception('The second page is different of 2.');
        }
    }

    /**
     * @When I request the recipes endpoint passing the recipe Id
     */
    public function iRequestTheRecipesEndpointPassingTheRecipeId()
    {
        $this->response = $this->http->get('recipes/1');
    }

    /**
     * @Then I get the data for that recipe
     */
    public function iGetTheDataForThatRecipe()
    {
        $this->checkStatusCode(200);

        $data = $this->getResponseData();

        if (!array_key_exists('data', $data)) {
            throw new \Exception('The key "data" is not present in response.');
        }
        if (!array_key_exists('name', $data['data'])) {
            throw new \Exception('The key "name" is not present in response.');
        }

        if (!array_key_exists('description', $data['data'])) {
            throw new \Exception('The key "description" is not present in response.');
        }

        if (!array_key_exists('average_rating', $data['data'])) {
            throw new \Exception('The key "average_rating" is not present in response.');
        }

        if (array_key_exists('meta', $data)) {
            throw new \Exception('The key "meta" is present in response and must not.');
        }
    }

    /**
     * @When I request the recipes endpoint passing the page size
     */
    public function iRequestTheRecipesEndpointPassingThePageSize()
    {
        $this->response = $this->http->get('recipes?per_page=3');
    }

    /**
     * @Then I get the data for that recipe with a different page size
     */
    public function iGetTheDataForThatRecipeWithADifferentPageSize()
    {
        $this->checkStatusCode(206);

        $data = $this->getResponseData();

        if (!array_key_exists('data', $data)) {
            throw new \Exception('The key "data" is not present in response.');
        }
        if (count($data['data']) != 3) {
            throw new \Exception('Spected 3 items and got ' . count($data['data']));
        }

        if (!array_key_exists('meta', $data)) {
            throw new \Exception('The key "meta" is not present in response.');
        }

        if ($data['meta']['pagination']['per_page'] != 3) {
            throw new \Exception('Spected "per_page" must be equals 3 and got ' . $data['meta']['pagination']['per_page']);
        }
    }

    /**
     * @Given I am an autheticated user
     */
    public function iAmAnAutheticatedUser()
    {
        $res = $this->http->post('auth', [
            'json' => [
                'username' => $this->username,
                'password' => $this->password,
            ]
        ]);
        $this->token = json_decode($res->getBody(), true)['token'];
    }

    /**
     * @When I submit the new recipe
     */
    public function iSubmitTheNewRecipe()
    {
        $this->response = $this->http->post('recipes', [
            'headers' => [
                'Authorization' => $this->token
            ],
            'json' => [
                'name'        => 'My fabulous recipe',
                'description' => 'A really long description for this incredible recipe',
                'difficulty'  => 2,
                'prep_time'   => 60,
                'vegetarian'  => false
            ]
        ]);
    }

    /**
     * @Then I get :code and see the new recipe data
     * @param mixed $code
     */
    public function iGetAndSeeTheNewRecipeData($code)
    {
        $this->checkStatusCode($code);

        $data = $this->getResponseData();

        if (!array_key_exists('data', $data)) {
            throw new \Exception('The key "data" is not present in response.');
        }

        $data = $data['data'];

        if (!array_key_exists('id', $data)) {
            throw new \Exception('The key "id" is not present in response.');
        }

        if (!array_key_exists('average_rating', $data)) {
            throw new \Exception('The key "average_rating" is not present in response.');
        }

        if ($data['average_rating'] != 0) {
            throw new \Exception('The key "average_rating" is different of 0.');
        }

        if (!array_key_exists('created_at', $data)) {
            throw new \Exception('The key "created_at" is not present in response.');
        }

        if (!array_key_exists('updated_at', $data)) {
            throw new \Exception('The key "updated_at" is not present in response.');
        }

        unset($data['id']);
        unset($data['average_rating']);
        unset($data['created_at']);
        unset($data['updated_at']);

        $createdRecipe = [
            'name'        => 'My fabulous recipe',
            'description' => 'A really long description for this incredible recipe',
            'difficulty'  => 2,
            'prep_time'   => 60,
            'vegetarian'  => false
        ];

        if ($data != $createdRecipe) {
            throw new \Exception('The response recipe has not equal data.');
        }
    }

    /**
     * @Then I see the new recipe in recipes first page
     */
    public function iSeeTheNewRecipeInRecipesFirstPage()
    {
        $this->response = $this->http->get('recipes');
        $data           = $this->getResponseData();

        $data = $data['data'][0];

        unset($data['id']);
        unset($data['average_rating']);
        unset($data['created_at']);
        unset($data['updated_at']);

        $createdRecipe = [
            'name'        => 'My fabulous recipe',
            'description' => 'A really long description for this incredible recipe',
            'difficulty'  => 2,
            'prep_time'   => 60,
            'vegetarian'  => false
        ];

        if ($data != $createdRecipe) {
            throw new \Exception('The response recipe has not equal data in the recipes first page.');
        }
    }

    /**
     * @Given I am an unautheticated user
     */
    public function iAmAnUnautheticatedUser()
    {
        return true;
    }

    /**
     * @When I submit the new recipe without JWT token
     */
    public function iSubmitTheNewRecipeWithoutJwtToken()
    {
        $this->response = $this->http->post('recipes', [
            'json' => [
                'name'        => 'My fabulous recipe',
                'description' => 'A really long description for this incredible recipe',
                'difficulty'  => 2,
                'prep_time'   => 60,
                'vegetarian'  => false
            ]
        ]);
    }

    /**
     * @Then I get :code and see the error message
     * @param mixed $code
     */
    public function iGetAndSeeTheErrorMessage($code)
    {
        $this->checkStatusCode($code);
    }

    /**
     * @Given I an anonymous user
     */
    public function iAnAnonymousUser()
    {
        return true;
    }

    /**
     * @When I submit the rateing for the recipe
     */
    public function iSubmitTheRateingForTheRecipe()
    {
        $recipes = $this->http->get('recipes');
        $id = json_decode($recipes->getBody(), true)['data'][0]['id'];

        $this->response = $this->http->post("recipes/{$id}/rating", [
            'json' => [
                'rating' => 5,
            ]
        ]);
    }

    /**
     * @Then I get :code and see the new rating average for the recipe
     */
    public function iGetAndSeeTheNewRatingAverageForTheRecipe($code)
    {
        $this->checkStatusCode($code);
        $data = $this->getResponseData();

        if ($data['data']['average_rating'] != 5) {
            throw new \Exception('Spected "average_rating" to be equals 5, but got ' . $data['data']['average_rating']);
        }
    }

    /**
     * @Then I see the new recipe with new stats in recipes first page
     */
    public function iSeeTheNewRecipeWithNewStatsInRecipesFirstPage()
    {
        $this->response = $this->http->get('recipes');
        $this->checkStatusCode(206);
        $data = $this->getResponseData();

        if ($data['data'][0]['average_rating'] != 5) {
            throw new \Exception('Spected "average_rating" to be equals 5, but got ' . $data['data']['average_rating']);
        }
    }
}
