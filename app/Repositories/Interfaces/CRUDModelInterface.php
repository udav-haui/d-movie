<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

/**
 * Interface CRUDModelInterface
 *
 * @package App\Repositories\Interfaces
 */
interface CRUDModelInterface
{
    /**
     * Retrieve model
     *
     * @param string|int $id
     * @return Model
     */
    public function find($id);

    /**
     * Create new record for model
     *
     * @param array $fields
     * @return mixed
     */
    public function create($fields = []);

    /**
     * Update model data
     *
     * @param string|int|null $modelId
     * @param array $fields
     * @param Model|null $model
     * @return void
     */
    public function update($modelId = null, $model = null, $fields = []);

    /**
     * @param null|string|int $modelId
     * @param null|Model $model
     * @return bool
     */
    public function delete($modelId, $model = null);

    /**
     * Remove _token field.
     *
     * @param array $fields
     * @return array
     */
    public function removeTokenField($fields);

    /**
     * Remove _method field if exist
     *
     * @param array $fields
     * @return array
     */
    public function removeMethodField($fields);
}
