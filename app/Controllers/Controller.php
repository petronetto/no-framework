<?php

declare(strict_types=1);

namespace HelloFresh\Controllers;

use Petronetto\Http\AbstractController;

/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     host="localhost:8080",
 *     schemes={"http", "https"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="HelloFresh Recipes API",
 *         description="HelloFresh Recipes API - By Juliano Petronetto",
 *         @SWG\Contact(name="Juliano Petronetto", url="http://petronetto.com.br"),
 *     ),
 *     @SWG\Definition(
 *         definition="Error",
 *         description="",
 *         type="object",
 *         @SWG\Property(property="error", type="string"),
 *         @SWG\Property(property="code", type="integer")
 *     ),
 * )
 */
class Controller extends AbstractController
{
}
