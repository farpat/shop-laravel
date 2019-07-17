<?php

namespace App\ViewComposers;

use App\Repositories\ModuleRepository;
use Illuminate\View\View;

class UsersList
{
    /**
     * @var ModuleRepository
     */
    private $userRepository;

    public function __construct (ModuleRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function compose (View $view)
    {
        $view->with('users', $this->userRepository->getRandom());
    }
}
