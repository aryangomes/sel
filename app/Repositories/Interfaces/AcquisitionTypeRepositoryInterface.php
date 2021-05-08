<?php

namespace App\Repositories\Interfaces;

interface AcquisitionTypeRepositoryInterface extends RepositoryEloquentInterface
{
    /**
     * @return Collection
     */
    public function findAll();
}
