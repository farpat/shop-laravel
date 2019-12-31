<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    /**
     * @return Collection|User[]
     */
    public function getAll (): Collection
    {
        return User::query()->orderBy('id')->get();
    }

    public function update (User $user, array $data)
    {
        $user->update($data);
    }
}