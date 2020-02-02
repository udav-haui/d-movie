<?php

namespace App\Repositories\Abstracts;
use App\Repositories\Interfaces\CRUDModelInterface;
use Illuminate\Database\Eloquent\Model;

/**
 * Class CRUDModelAbstract
 *
 * @package App\Repositories\Abstracts
 */
abstract class CRUDModelAbstract implements CRUDModelInterface
{
    /** @var Model */
    protected $model;

    /**
     * Create new record for model
     *
     * @param array $fields
     * @return mixed
     */
    public function create($fields = [])
    {
        $fields = $this->removeTokenField($fields);

        $modelId = $this->model::query()->insertGetId($fields);

        return $this->model::find($modelId);
    }

    /**
     * Update model data
     *
     * @param string|int $modelId
     * @param array $fields
     * @return bool
     */
    public function update($modelId, $fields = [])
    {
        $fields = $this->removeTokenField($fields);

        $model = $this->model::find($modelId);

        $model->update($fields);

        return $model;
    }

    /**
     * Remove _token field.
     *
     * @param array $fields
     * @return array
     */
    public function removeTokenField($fields)
    {
        if (array_key_exists('_token', $fields)) {
            unset($fields['_token']);
        }

        return $fields;
    }
}
