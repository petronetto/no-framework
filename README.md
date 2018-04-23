```
  _   _         ______                                           _    
 | \ | |       |  ____|                                         | |   
 |  \| | ___   | |__ _ __ __ _ _ __ ___   _____      _____  _ __| | __
 | . ` |/ _ \  |  __| '__/ _` | '_ ` _ \ / _ \ \ /\ / / _ \| '__| |/ /
 | |\  | (_) | | |  | | | (_| | | | | | |  __/\ V  V / (_) | |  |   < 
 |_| \_|\___/  |_|  |_|  \__,_|_| |_| |_|\___| \_/\_/ \___/|_|  |_|\_\

```

> This is just for study case, use by your own risk!!!

# TL;DR

Run `./nofw init` to build the application 🚀


## API Architecture

I used the follow packages to compose the API:
* [Zend Diactoros](https://github.com/zendframework/zend-diactoros) A PSR-7 HTTP Message implementation
* [Zend Stratigility](https://github.com/zendframework/zend-stratigility) A PSR-7 middleware foundation for building and dispatching middleware pipelines
* [PHP-DI](https://github.com/PHP-DI/PHP-DI) A container for dependency injection
* [FastRoute](https://github.com/nikic/FastRoute) A request router
* [Illuminate Database](https://github.com/illuminate/database) An activeRecord style ORM, and schema builder
* [Phinx](https://github.com/cakephp/phinx) Tool to migrate and seed the database
* [Respect Validation](https://github.com/Respect/Validation) A validation engine to validate incoming requests
* [Illuminate Pagination](https://github.com/illuminate/pagination) An component to paginate the ORM results
* [League Fractal](https://github.com/thephpleague/fractal) Helps to output complex RESTful data structures
* [Monolog](https://github.com/Seldaek/monolog) A simple logger to the API
* [Lcobucci JWT](https://github.com/lcobucci/jwt) Provide a JWT implementation
* [Predis](https://github.com/nrk/predis) PHP Redis client

For dev:
* [PHPUnit](https://github.com/sebastianbergmann/phpunit) Unit tests
* [PHPUnit Pretty Result Printer](https://github.com/mikeerickson/phpunit-pretty-result-printer) A pretty printer for PHPUnit
* [Mockery](https://github.com/mockery/mockery) PHP mock object framework for use in unit testing with PHPUnit
* [Behat](https://github.com/behat/behat) A BDD framework
* [Guzzle](https://github.com/guzzle/guzzle) A HTTP client to make request in BDD tests
* [Faker](https://github.com/fzaninotto/Faker) Generates fake data for dev environment and unit tests
* [Swagger PHP](https://github.com/zircote/swagger-php) A PHP swagger annotation and parsing library
* [PHP CodeSniffer](https://github.com/squizlabs/PHP_CodeSniffer) A tool ensure the coding standards
* [PHP-CS-Fixer](https://github.com/FriendsOfPHP/PHP-CS-Fixer) A tool to automatically fix PHP coding standards issues
* [Symfony VarDumper](https://github.com/symfony/var-dumper) Provides a better dump() function


## Running the application

I create a utility tool for command line, run the application simply run `./nofw init`.
Run `./nofw` to see another options.


## Documentation

The application ships with [Swagger](https://swagger.io/) docs, go to [ttp://localhost:4000](ttp://localhost:4000) to see.
Some endpoints require authentication, go to the [http://localhost:4000/#/auth/post_auth](http://localhost:4000/#/auth/post_auth) and send the request to get the JWT token, now click in button `Authorize` in right superior corner, and put the token there.


## Quality Assurance

The application already ships with a simple container of [SonarQube](https://www.sonarqube.org/), to see the application stats, first you need run the Unit tests in order to generate the coverage, simply run `./nofw tests`, after the tests finish, run `./nofw sonar-runner`, now go to the [http://localhost:9000/dashboard?id=App](http://localhost:9000/dashboard?id=App).
