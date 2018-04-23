<?php

declare(strict_types=1);

namespace Petronetto\Transformers;

use Petronetto\ORM\ORMInterface;

interface TransformerInterface
{
    public function transform(ORMInterface $model);
}
