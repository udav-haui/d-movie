<?php

namespace App\Repositories\Abstracts;

use App\Exceptions\CannotDeleteException;
use App\Exceptions\NoChangedException;
use App\Exceptions\UnknownException;
use App\Repositories\Interfaces\CRUDModelInterface;
use App\Repositories\LoggerTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Exception;
use Illuminate\Support\Facades\Storage;
use function Sodium\add;

/**
 * Class CRUDModelAbstract
 *
 * @package App\Repositories\Abstracts
 */
abstract class CRUDModelAbstract implements CRUDModelInterface
{
    use LoggerTrait;

    /** @var Model */
    protected $model;

    /**
     * @param array $ids
     * @return \Illuminate\Support\Collection
     */
    public function getByIds(array $ids = [])
    {
        $data = collect();

        foreach ($ids as $id) {
            $model = $this->find($id);
            $data->add($model);
        }

        return $data;
    }

    /**
     * Get all records
     *
     * @param array $withTbl
     * @param bool $isVisible
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Collection|Model[]
     */
    public function all($withTbl = [], $isVisible = false)
    {
        $collection = $this->model::query();
        if ($isVisible) {
            $collection = $collection->whereStatus(1);
        }

        if (!empty($withTbl)) {
            $collection = $collection->with($withTbl);
        }
        return $collection->get();
    }

    /**
     * Search data by array key-value
     *
     * @param null $collection
     * @param array $fields
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function searchBy($collection = null, $fields = [])
    {
        try {
            if ($collection === null) {
                $collection = $this->model::query();
            }

            foreach ($fields as $key => $value) {
                $collection = $collection->where($key, 'like', '%' . $value . '%');
            }

            return $collection;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get visible data
     *
     * @param null $collection
     * @param array $withTbl
     * @return \Illuminate\Database\Eloquent\Builder
     * @throws Exception
     */
    public function getVisible($collection = null, $withTbl = [])
    {
        try {
            if ($collection === null) {
                $collection = $this->model::query();
            }

            if (!empty($withTbl)) {
                $collection = $collection->with($withTbl);
            }

            $collection = $collection->where('status', $this->model::ENABLE);

            return $collection;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Filter data
     *
     * @param null $query
     * @param array $filterArr
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Builder|null
     * @throws Exception
     */
    public function getFilter($query = null, $filterArr = [], $with = [])
    {
        try {
            if ($query === null) {
                $query = $this->model::query();
            }

            if (!empty($filterArr)) {
                foreach ($filterArr as $key => $value) {
                    $query = $query->where($key, $value);
                }
            }

            if (!empty($with)) {
                $query = $query->with($with);
            }

            return $query;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Get order by
     *
     * @param null $query
     * @param array $columns
     * @return \Illuminate\Database\Eloquent\Builder|null
     * @throws Exception
     */
    public function orderBy($query = null, array $columns = [])
    {
        try {
            if ($query === null) {
                $query = $this->model::query();
            }

            if (!empty($columns)) {
                foreach ($columns as $key => $value) {
                    $query = $query->orderBy($key, $value);
                }
            }

            return $query;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

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
     * @param bool $isWriteLog
     * @return mixed
     * @throws Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {
        $fields = $this->removeTokenField($fields);

        try {
            $fields = $this->encodeSpecialChar($fields);
            $model = $this->model::create($fields);

            if ($isWriteLog) {
                $this->createLog($model, $this->model);
            }
            return $model;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function update(
        $modelId = null,
        $model = null,
        $fields = [],
        bool $isWriteLog = true,
        bool $encodeSpecChar = true,
        $nonUpdateFields = [],
        $removedToLogFields = [],
        bool $useUpdateInputFieldToLog = false
    ) {
        $fields = $this->removeTokenField($fields);
        $fields = $this->removeMethodField($fields);

        if ($modelId !== null) {
            $model = $this->find($modelId);
        }

        try {
            if (count($fields) > 0) {
                if ($encodeSpecChar) {
                    $fields = $this->encodeSpecialChar($fields);
                }
                $oldData = clone $model;
                $keepedUpdateData = $fields;
                if ($this->getChanged($oldData, $fields, $removedToLogFields)) {
                    if ($nonUpdateFields) {
                        $fields = array_diff_key($fields, array_flip($nonUpdateFields));
                    }
                    $model->update($fields);
                    $newLogData = $useUpdateInputFieldToLog ? [$model->id => $keepedUpdateData] : $model;

                    // Config model
                    if ($this->model == \App\Config::class) {
                        $specificKeyToReplaceInLog = [
                            "replace_key" => $model->getSectionId(),
                            "to_key" => "config_value"
                        ];
                    }

                    if ($isWriteLog) {
                        $this->log(
                            $oldData,
                            $newLogData,
                            $this->model,
                            null,
                            'update',
                            $this->getMergeNeverBeChangedFields($removedToLogFields),
                            $specificKeyToReplaceInLog ?? []
                        );
                    }
                    return $model;
                }
                throw new NoChangedException(__("You changed nothing!"));
            }
            return false;
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->errorInfo[1] == 1451) {
                throw new CannotDeleteException(
                    __('Can not delete or update because the record has relation to other.')
                );
            }
            throw new UnknownException($e->getMessage());
        }
    }

    /**
     * Get merged nerver be changed fields
     *
     * @param array $neverChangedFieldInput
     * @return array
     */
    protected function getMergeNeverBeChangedFields($neverChangedFieldInput)
    {
        $defaultModelExceptField = [];
        if ($this->model == \App\Config::class) {
            $defaultModelExceptField = ['section_id'];
        }
        return array_merge(
            $defaultModelExceptField,
            $neverChangedFieldInput,
            $this->getDefaultNoCompareField()
        );
    }

    /**
     * Get be changed value
     *
     * @param array|Model $oldData
     * @param array $newData
     * @param array $neverBeChangedFields
     * @return array
     */
    protected function getChanged(
        $oldData,
        $newData,
        $neverBeChangedFields = []
    ) {
        $neverBeChangedFields = $this->getMergeNeverBeChangedFields($neverBeChangedFields);

        if (!is_array($oldData)) {
            $oldData = $oldData->toArray();
        }
        $oldData = unset_no_compare_field($oldData, $neverBeChangedFields);
        return $this->arrayDiffRecursive(
            $newData,
            $oldData
        );
    }

    /**
     * Get default no compare field
     *
     * @return string[]
     */
    private function getDefaultNoCompareField()
    {
        return ['id', 'created_at', 'updated_at'];
    }

    /**
     * @param null|int|string $modelId
     * @param null|Model $model
     * @param bool $isWriteLog
     * @return bool|Model
     * @throws Exception
     */
    public function delete($modelId = null, $model = null, bool $isWriteLog = true)
    {
        try {
            if ($modelId !== null) {
                $model = $this->find($modelId);
            }
            $model->delete();
            if ($isWriteLog) {
                $this->deleteLog($model, $this->model);
            }
            return $model;
        } catch (\Illuminate\Database\QueryException $exception) {
            if ($exception->errorInfo[1] == 1451) {
                throw new Exception(__('Can not delete or update because the record has relation to other.'));
            }
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

    /**
     * Remove ids field from request
     *
     * @param array $fields
     * @return mixed
     */
    public function removeIdsKey($fields)
    {
        if (array_key_exists('ids', $fields)) {
            unset($fields['ids']);
        }

        return $fields;
    }

    /**
     * Store image to storage
     *
     * @param object $image
     * @return string - image path
     * @throws Exception
     */
    public function storeImage($image)
    {
        try {
            return $image->store('uploads', 'public');
        } catch (Exception $e) {
            throw new Exception(__('We cannot upload your image.'));
        }
    }

    /**
     * Delete file file in storage
     *
     * @param string $filePath
     * @return bool
     */
    public function deleteLocalFile($filePath)
    {
        return Storage::delete('/public/' . $filePath);
    }

    /**
     * Format a date to insert to db
     *
     * @param string $date
     * @return string
     */
    public function formatDate(string $date)
    {
        return Carbon::make($date)->format('Y-m-d');
    }

    /**
     * @param array $fields
     * @return array
     */
    protected function encodeSpecialChar(array $fields)
    {
        foreach ($fields as $key => $value) {
            if ($value) {
                if (is_array($value)) {
                    $fields[$key] = $this->encodeSpecialChar($value);
                } else {
                    $fields[$key] = htmlspecialchars($value);
                }
            }
        }

        return $fields;
    }
}
