<?php


namespace App\Actions\CrudModelOperations;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Create
{
    /**
     * @var Model
     */
    private $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     *
     * @param string $modelClass
     * @param array $dataToCreate
     * @return Model
     */
    public function __invoke(array $dataToCreate): Model
    {
        try {
            DB::beginTransaction();

            $modelCreated = $this->model::create($dataToCreate);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
        }

        return $modelCreated;
    }
}
