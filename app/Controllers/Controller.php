<?php

declare(strict_types=1);

namespace App\Controllers;

use Petronetto\Http\AbstractController;

/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     host="localhost:8080",
 *     schemes={"http"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="App Recipes API",
 *         description="App Recipes API - By Juliano Petronetto",
 *         @SWG\Contact(name="Juliano Petronetto", url="http://petronetto.com.br"),
 *     ),
 *     @SWG\SecurityScheme(
 *         description="The JWT token. Get in /api/v1/auth",
 *         securityDefinition="Authorization",
 *         type="apiKey",
 *         name="Authorization",
 *         in="header"
 *     ),
 * ),
 *
 * @SWG\Definition(
 *     definition="Meta",
 *     type="object",
 *     @SWG\Property(
 *         property="pagination",
 *         @SWG\Property(property="total", type="integer", example=30),
 *         @SWG\Property(property="count", type="integer", example=15),
 *         @SWG\Property(property="per_page", type="integer", example=15),
 *         @SWG\Property(property="current_page", type="integer", example=2),
 *         @SWG\Property(property="total_pages", type="integer", example=5),
 *         @SWG\Property(
 *             property="links",
 *             @SWG\Property(property="previous", type="string", example="/api/v1/recipes?page=1"),
 *             @SWG\Property(property="next", type="string", example="/api/v1/recipes?page=3"),
 *         ),
 *     ),
 * ),
 *
 * @SWG\Definition(
 *     definition="Error",
 *     description="",
 *     type="object",
 *     @SWG\Property(property="type", type="string", example="Exception"),
 *     @SWG\Property(property="message", type="string", example="Something went wrong"),
 *     @SWG\Property(property="code", type="string", example=400),
 * ),
 */
class Controller extends AbstractController
{
}
