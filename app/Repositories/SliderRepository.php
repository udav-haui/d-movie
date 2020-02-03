<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use App\Slider;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Storage;

/**
 * Class SliderRepository
 *
 * @package App\Repositories
 */
class SliderRepository extends CRUDModelAbstract implements SliderRepositoryInterface
{
    use LoggerTrait;

    /** @var string */
    protected $model = Slider::class;

    /**
     * Create new Slide item image
     *
     * @param array $fields
     * @return mixed
     * @throws \Exception
     */
    public function create($fields = [])
    {
        try {
            if ($fields['image']) {
                $uploadImage = $fields['image'];

                try {
                    $imgPath = $uploadImage->store('uploads', 'public');
                } catch (\Exception $e) {
                    throw new Exception(__('Cannot upload your image.'));
                }

                $fields['image'] = $imgPath;
            }

            $slider = parent::create($fields);

            $this->createLog($slider, \App\Slider::class);

            return $slider;
        } catch (\Exception $exception) {
            Storage::delete('/public/' . $imgPath);

            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * Update model data
     *
     * @param string|int|null $sliderId
     * @param array $fields
     * @param Slider $slider
     * @return Slider|\Illuminate\Database\Eloquent\Model
     * @throws \Exception
     */
    public function update($sliderId = null, $slider = null, $fields = [])
    {
        try {
            if (array_key_exists('image', $fields)) {
                if ($slider) {
                    Storage::delete('/public/' . $slider->getAttribute('image'));
                }
            }

            $slider = parent::update($sliderId, $slider, $fields);

            $this->updateLog($slider, \App\Slider::class);

            return $slider;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
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
     * @throws \Exception
     */
    public function changeStatus($slider, $newStatus)
    {
        try {
            $this->update(null, $slider, ['status' => $newStatus]);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
