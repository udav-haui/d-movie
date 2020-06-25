<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use App\Slider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Exception;

/**
 * Class SliderRepository
 *
 * @package App\Repositories
 */
class SliderRepository extends CRUDModelAbstract implements SliderRepositoryInterface
{
    use LoggerTrait;

    protected $model = Slider::class;

    /**
     * Create new Slide item image
     *
     * @param array $fields
     * @param bool $isWriteLog
     * @return mixed
     * @throws Exception
     */
    public function create($fields = [], bool $isWriteLog = true)
    {
        try {
            if ($fields['image']) {
                $uploadImage = $fields['image'];

                $fields['image'] = $this->storeImage($uploadImage);
            }

            return parent::create($fields);
        } catch (Exception $exception) {
            if ($fields['image']) {
                Storage::delete('/public/' . $fields['image']);
            }

            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function update(
        $sliderId = null,
        $slider = null,
        $fields = [],
        bool $isWriteLog = true,
        bool $encodeSpecChar = true,
        $nonUpdateFields = [],
        $removedToLogFields = [],
        bool $useUpdateInputFieldToLog = false
    ) {
        try {
            if (array_key_exists('image', $fields)) {
                if ($slider) {
                    Storage::delete('/public/' . $slider->getImage());
                }
                $uploadImage = $fields['image'];

                try {
                    $imgPath = $uploadImage->store('uploads', 'public');
                } catch (Exception $e) {
                    throw new Exception(__('Cannot upload your image.'));
                }

                $fields['image'] = $imgPath;
            }

            $slider = parent::update($sliderId, $slider, $fields);

            $this->updateLog($slider, Slider::class);

            return $slider;
        } catch (Exception $exception) {
            throw new Exception($exception->getMessage());
        }
    }

    /**
     * @param null|int|string $sliderId
     * @param null|Slider $slider
     * @param bool $isWriteLog
     * @return bool
     * @throws Exception
     */
    public function delete($sliderId = null, $slider = null, bool $isWriteLog = true)
    {
        if ($sliderId != null) {
            $slider = $this->find($sliderId);
        }
        if ($slider) {
            if ($slider->getImage()) {
                Storage::delete('/public/' . $slider->getImage());
            }
            try {
                parent::delete(null, $slider);

                $this->deleteLog($slider, Slider::class);
                return true;
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }
        throw new Exception(__('Please try again.'));
    }

    /**
     * Get slider collection by order
     *
     * @return Collection|Slider[]
     */
    public function allByOrder()
    {
        return Slider::orderBy('order', 'desc')->get();
    }

    /**
     * Change slide item status
     *
     * @param Slider $slider
     * @param string|int $newStatus
     * @return void
     * @throws Exception
     */
    public function changeStatus($slider, $newStatus)
    {
        try {
            $this->update(null, $slider, ['status' => $newStatus]);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
