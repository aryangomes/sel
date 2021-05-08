<?php

namespace App\Repositories\Interfaces;

/**
 * Interface RepositoryEloquentInterface
 * @package App\Repositories
 */


interface RepositoryEloquentInterface
{
    /**
     * @param array $attributes
     * @return Model
     */
    public function create(array $attributes);

    /**
     * @param $id
     * @return void
     */
    public function delete($id);

    /**
     * @param $id
     * @return Model
     */
    public function findById($id);

    /**
     * 
     * @return Collection 
     */
    public function findAll();

    /**
     * @param  $id
     * @param array $attributes
     * @return Model
     */
    public function update($id, array $attributes);
}
