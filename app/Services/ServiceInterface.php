<?php

declare(strict_types=1);

namespace HelloFresh\Services;

interface ServiceInterface
{
    public function getAll();

    public function create(array $data);

    public function delete($id);

    public function getById($id);

    public function update($id, array $data);

    public function query();
}
