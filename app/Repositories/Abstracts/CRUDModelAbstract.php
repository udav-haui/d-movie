<?php

namespace App\Repositories\Abstracts;
use App\Repositories\Interfaces\CRUDModelInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

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
     * @param string|int|null $modelId
     * @param array $fields
     * @param Model|null $model
     * @return Model
     * @throws \Exception
     */
    public function update($modelId = null, $model = null, $fields = [])
    {
        $fields = $this->removeTokenField($fields);
        $fields = $this->removeMethodField($fields);

        if ($modelId !== null) {
            $model = $this->model::find($modelId);
        }

        try {
            if (array_key_exists('image', $fields)) {
                $uploadImage = $fields['image'];

                try {
                    $imgPath = $uploadImage->store('uploads', 'public');
                } catch (\Exception $e) {
                    throw new \Exception(__('Cannot upload your image.'));
                }

                $fields['image'] = $imgPath;
            }


            $model->update($fields);

            return $model;
        } catch (\Exception $e) {
            if (array_key_exists('image', $fields)) {
                Storage::delete('/public/' . $imgPath);
            }
            throw new \Exception($e->getMessage());
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
