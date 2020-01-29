<?php

namespace App\Composers;

use App\Repositories\Interfaces\UserRepositoryInterface;

class GlobalComposer
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * GlobalComposer constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(
        UserRepositoryInterface $userRepository
    ) {
        $this->userRepository = $userRepository;
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

        $view->with('activeUsers', $users);
    }
}
