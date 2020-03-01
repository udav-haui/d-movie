<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CinemaRepositoryInterface;
use Illuminate\Http\Request;

/**
 * Class CinemaController
 *
 * @package App\Http\Controllers
 */
class CinemaController extends Controller
{
    /**
     * @var CinemaRepositoryInterface
     */
    private $cinemaRepository;

    /**
     * CinemaController constructor.
     * @param CinemaRepositoryInterface $cinemaRepository
     */
    public function __construct(
        CinemaRepositoryInterface $cinemaRepository
    ) {

        $this->cinemaRepository = $cinemaRepository;
    }

    /**
     * Get list cinemas
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList()
    {
        if (request()->has('name')) {
            $cinemas = $this->cinemaRepository->searchBy(null, ['name' => request()->get('name')]);
            $cinemas = $this->cinemaRepository->getVisible($cinemas)->get();
        } else {
            $cinemas = $this->cinemaRepository->getVisible()->get();
        }

        return response()->json([
            'status' => 200,
            'data' => $cinemas
        ]);
    }
}
