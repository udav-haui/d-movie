<?php

namespace App\Repositories\Interfaces;

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
     * @param string|int $modelId
     * @param array $fields
     * @return bool
     */
    public function update($modelId, $fields = []);
}
