<?php

namespace App\Repositories;

use App\Repositories\Abstracts\CRUDModelAbstract;
use App\Repositories\Interfaces\ShowRepositoryInterface;
use App\Show;

/**
 * Class ShowRepository
 *
 * @package App\Repositories
 */
class ShowRepository extends CRUDModelAbstract implements ShowRepositoryInterface
{
    use LoggerTrait;

    protected $model = Show::class;

    /**
     * Create new record for model
     *
     * @param array $fields
     * @return mixed
     * @throws \Exception
     */
    public function create($fields = [])
    {
        try {
            $show = parent::create($fields);

            $this->createLog($show, Show::class);

            return $show;
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param null|int|string $modelId
     * @param null|Model $model
     * @return bool|Show
     * @throws \Exception
     */
    public function delete($modelId = null, $model = null)
    {
        try {
            $show = parent::delete($modelId, $model); // TODO: Change the autogenerated stub

            if ($show) {
                $this->deleteLog($show, Show::class);
                return $show;
            }
            throw new \Exception(__('Please try again.'));

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Update model data
     *
     * @param string|int|null $showId
     * @param array $fields
     * @param Show|null $show
     * @return Show
     * @throws \Exception
     */
    public function update($showId = null, $show = null, $fields = [])
    {
        try {
            if ($showId !== null) {
                /** @var Show $show */
                $show = $this->find($showId);
            }

            $show = parent::update(null, $show, $fields);

            return $show;

        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}