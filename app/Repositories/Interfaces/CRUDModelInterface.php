<?php

namespace App\Repositories\Interfaces;

use App\Exceptions\CannotDeleteException;
use App\Exceptions\NoChangedException;
use App\Exceptions\UnknownException;
use Illuminate\Database\Eloquent\Model;

/**
 * Interface CRUDModelInterface
 *
 * @package App\Repositories\Interfaces
 */
interface CRUDModelInterface
{

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    public function getByIds(array $ids = []);

    /**
     * Get all records
     *
     * @param array $withOpts
     * @param bool $isVisible
     * @return \Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all($withOpts = [], $isVisible = false);

    /**
     * Search data by array key-value
     *
     * @param null $collection
     * @param array $fields
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function searchBy($collection = null, $fields = []);

    /**
     * Get visible data
     *
     * @param null $collection
     * @param array $withTbl
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getVisible($collection = null, $withTbl = []);

    /**
     * Filter data
     *
     * @param null $query
     * @param array $filterArr
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Builder|null
     * @throws Exception
     */
    public function getFilter($query = null, $filterArr = [], $with = []);

    /**
     * Get order by
     *
     * @param null $query
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder|null
     * @throws \Exception
     */
    public function orderBy($query = null, array $columns = []);

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
     * @param bool $isWriteLog
     * @return mixed
     */
    public function create($fields = [], bool $isWriteLog = true);

    /**
     * Update model data
     *
     * @param string|int|null $modelId
     * @param Model|null $model
     * @param array $fields
     * @param bool $isWriteLog
     * @param bool $encodeSpecChar
     * @return void
     * @throws NoChangedException
     * @throws CannotDeleteException
     * @throws UnknownException
     */
    public function update(
        $modelId = null,
        $model = null,
        $fields = [],
        bool $isWriteLog = true,
        bool $encodeSpecChar = true
    );

    /**
     * @param null|string|int $modelId
     * @param null|Model $model
     * @param bool $isWriteLog
     * @return bool
     */
    public function delete($modelId, $model = null, bool $isWriteLog = true);

    /**
     * Remove _token field.
     *
     * @param array $fields
     * @return array
     */
    public function removeTokenField(array $fields);

    /**
     * Remove _method field if exist
     *
     * @param array $fields
     * @return array
     */
    public function removeMethodField(array $fields);

    /**
     * Remove ids field from request
     *
     * @param array $fields
     * @return mixed
     */
    public function removeIdsKey(array $fields);

    /**
     * Store image to storage
     *
     * @param object $image
     * @return string
     * @throws \Exception
     */
    public function storeImage($image);

    /**
     * Delete file file in storage
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteLocalFile($filePath);

    /**
     * Format a date to insert to db
     *
     * @param string $date
     * @return string
     */
    public function formatDate(string $date);
}
