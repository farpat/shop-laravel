<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Middleware\Authenticate;
use App\Http\Requests\{UserInformationsRequest, UserPasswordRequest};
use App\Models\{Address, User};
use App\Notifications\UserPasswordNotification;
use App\Repositories\{AddressRepository, BillingRepository, CartRepository, UserRepository};
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct (UserRepository $userRepository)
    {
        $this->middleware(Authenticate::class);
        $this->userRepository = $userRepository;
    }

    public function profile (Request $request)
    {
        $user = $request->user();
        return view('users.profile', compact('user'));
    }

    public function showInformationsForm (Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $old = $request->old();
        if ($old === [] || $old === null) {
            $form = [
                'name'      => $user->name,
                'email'     => $user->email,
                'addresses' => $user->addresses->map(function (Address $address, $index) {
                    $toArray = $address->toArray();
                    $toArray['index'] = $index;
                    return $toArray;
                })->toArray()
            ];
        } else {
            $form = $old;
        }

        return view('users.informations', compact('form'));
    }

    public function updateInformations (UserInformationsRequest $request, AddressRepository $addressRepository)
    {
        $user = $request->user();

        $this->userRepository->update($user, $request->only('name', 'email'));
        $addressRepository->setAddresses($user, $request->input('addresses', []));

        return redirect()->back()->with('success', __('User informations updated with success'));
    }

    public function showPasswordForm (Request $request)
    {
        return view('users.password');
    }

    public function updatePassword (UserPasswordRequest $request, Hasher $hasher)
    {
        /** @var User $user */
        $user = $request->user();
        $newPassword = $request->input('new_password');

        $this->userRepository->update($user, ['password' => $hasher->make($newPassword)]);

        $user->notify(new UserPasswordNotification());

        return redirect()
            ->back()
            ->with('success', __('User password updated with success'));
    }

    public function billings (Request $request, BillingRepository $bill)
    {
        $billings = $bill->get($request->user());

        return view('users.billings', compact('billings'));
    }
}
