<?php

namespace App\Actions\CrudModelOperations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Delete


{
    /**
     *
     * @param Model $model
     * @return void
     */
    public function __invoke(Model $model)
    {

        try {

            DB::beginTransaction();

            $modelWasDelete = $model->delete();
        } catch (\Exception $exception) {
        }

        $modelWasDelete ? DB::commit() : DB::rollBack();
    }
}
