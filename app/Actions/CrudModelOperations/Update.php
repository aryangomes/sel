<?php

namespace App\Actions\CrudModelOperations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Update
{

    /**
     * 
     * @param Model $model
     * @param array $dataToUpdate
     * @return void
     */
    public function __invoke(array $dataToUpdate, Model $model): void
    {
        try {
            DB::beginTransaction();
            $modelWasUpdate = $model->update($dataToUpdate);
        } catch (\Exception $exception) {
        }

        $modelWasUpdate ? DB::commit() : DB::rollBack();
    }
}
