<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Collection;

class UserRepository
{
    public function getAll (): Collection
    {
        return User::query()
            ->orderBy('id')
            ->pluck('email', 'id');
    }

    public function update (User $user, array $data)
    {
        $user->update($data);
    }
}