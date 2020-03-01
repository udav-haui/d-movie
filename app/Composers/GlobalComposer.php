<?php

namespace App\Composers;

use App\Repositories\Interfaces\CinemaRepositoryInterface;
use App\Repositories\Interfaces\SliderRepositoryInterface;
use App\Repositories\Interfaces\StaticPageRepositoryInterface;
use App\Repositories\Interfaces\UserRepositoryInterface;

class GlobalComposer
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;
    /**
     * @var CinemaRepositoryInterface
     */
    private $cinemaRepository;
    /**
     * @var StaticPageRepositoryInterface
     */
    private $pageRepository;
    /**
     * @var SliderRepositoryInterface
     */
    private $sliderRepository;

    /**
     * GlobalComposer constructor.
     *
     * @param UserRepositoryInterface $userRepository
     * @param CinemaRepositoryInterface $cinemaRepository
     * @param StaticPageRepositoryInterface $pageRepository
     * @param SliderRepositoryInterface $sliderRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository,
        CinemaRepositoryInterface $cinemaRepository,
        StaticPageRepositoryInterface $pageRepository,
        SliderRepositoryInterface $sliderRepository
    ) {
        $this->userRepository = $userRepository;
        $this->cinemaRepository = $cinemaRepository;
        $this->pageRepository = $pageRepository;
        $this->sliderRepository = $sliderRepository;
    }

    /**
     * @param \Illuminate\View\View $view
     */
    public function compose($view)
    {
        /** @var array $fields */
        $fields = [
            'account_type' => \App\User::STAFF,
            'state' => \App\User::ACTIVE
        ];

        $users = $this->userRepository->getActiveUsers($fields);
        $cinemas = $this->cinemaRepository->getVisible()->get();

        $activePagesGlobal = $this->pageRepository->getFilter(null, [
            'language' => app()->getLocale()
        ]);
        $activePagesGlobal = $this->pageRepository->getVisible($activePagesGlobal)->get();

        $sliderquery = $this->sliderRepository->getVisible();
//
//        dd($sliderquery->orderBy('order', 'DESC')->get());
        $sliders = $this->sliderRepository->orderBy($sliderquery, ['order' => 'ASC'])->get();

        $view->with('activeUsers', $users);
        $view->with('activeCinemasGlobal', $cinemas);
        $view->with('activePagesGlobal', $activePagesGlobal);
        $view->with('activeSlidersGlobal', $sliders);
    }
}
