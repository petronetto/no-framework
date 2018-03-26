<?php

declare(strict_types=1);

namespace HelloFresh\Controllers;

use Petronetto\Http\AbstractController;

/**
 * @SWG\Swagger(
 *     basePath="/api/v1",
 *     host="localhost:8080",
 *     schemes={"http"},
 *     @SWG\Info(
 *         version="1.0",
 *         title="Help Center API",
 *         description="Help Center API by PicPay",
 *         @SWG\Contact(name="PicPay", url="https://ajuda.picpay.com"),
 *     ),
 *     @SWG\Definition(
 *         definition="Error",
 *         description="",
 *         type="object",
 *         @SWG\Property(property="error", type="string"),
 *         @SWG\Property(property="code", type="integer")
 *     ),
 *     @SWG\Definition(
 *         definition="ValidationError",
 *         type="object",
 *         @SWG\Property(property="attribute", type="array", @SWG\Items(type="string")),
 *         description="Returns an object, where each \*attribute\* is an array of errors of this attribute"
 *     )
 * )
 */
class Controller extends AbstractController
{
    //
}
