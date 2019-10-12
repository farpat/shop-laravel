<?php

namespace App\Repositories;

use App\Models\{Address, User};

class AddressRepository
{
    public function setAddresses (User $user, array $addressesArray)
    {
        if (empty($addressArray)) {
            return;
        }

        $deletedIds = [];
        foreach ($addressesArray as $addressArray) {
            if ($addressArray['is_deleted']) {
                $deletedIds[] = $addressArray['id'];
                continue;
            }

            $address = $addressArray['id'] === null ?
                new Address() :
                Address::query()->where('user_id', $user->id)->findOrFail($addressArray['id']);

            $address->fill($addressArray);
            $address->user_id = $user->id;
            $address->save();
        }

        if (!empty($deletedIds)) {
            Address::query()->whereIn('id', $deletedIds)->delete();
        }
    }
}