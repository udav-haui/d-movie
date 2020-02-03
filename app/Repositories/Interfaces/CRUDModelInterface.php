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
}
