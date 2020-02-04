<?php

namespace App\Repositories\Abstracts;

use App\Repositories\Interfaces\CRUDModelInterface;
use Illuminate\Database\Eloquent\Model;
use Exception;

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
     * Retrieve model
     *
     * @param int|string $id
     * @return Model
     */
    public function find($id)
    {
        return $this->model::find($id);
    }

    /**
     * Create new record for model
     *
     * @param array $fields
     * @return mixed
     * @throws Exception
     */
    public function create($fields = [])
    {
        $fields = $this->removeTokenField($fields);

        try {
            return $this->model::create($fields);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * Update model data
     *
     * @param string|int|null $modelId
     * @param array $fields
     * @param Model|null $model
     * @return Model
     * @throws Exception
     */
    public function update($modelId = null, $model = null, $fields = [])
    {
        $fields = $this->removeTokenField($fields);
        $fields = $this->removeMethodField($fields);

        if ($modelId !== null) {
            $model = $this->model::find($modelId);
        }

        try {
            $model->update($fields);

            return $model;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param null|int|string $modelId
     * @param null|Model $model
     * @return bool
     * @throws Exception
     */
    public function delete($modelId, $model = null)
    {
        try {
            $model->delete();
            return true;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
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

    /**
     * Remove _method field if exist
     *
     * @param array $fields
     * @return array
     */
    public function removeMethodField($fields)
    {
        if (array_key_exists('_method', $fields)) {
            unset($fields['_method']);
        }

        return $fields;
    }
}
