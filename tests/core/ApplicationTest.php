<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;

final class ApplicationTest extends TestCase
{
    public function test_application_instance(): void
    {
        $app = new Petronetto\Application(
            (new DI\ContainerBuilder)
                ->useAnnotations(false)
                ->addDefinitions(config()->get('di'))
                ->build()
        );
        $this->assertInstanceOf(
            \Petronetto\Application::class,
            $app
        );

        $this->assertInstanceOf(
            \DI\Container::class,
            $app->container
        );
    }
}
