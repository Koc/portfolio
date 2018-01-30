<?php

namespace App\Api;

interface CompositeModel
{
    /**
     * @return object[]
     */
    public function getPersistentObjects(): iterable;
}
