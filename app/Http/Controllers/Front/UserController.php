<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserInformationsRequest;
use App\Http\Requests\UserPasswordRequest;
use App\Models\User;
use App\Notifications\UserPasswordNotification;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;

class UserController extends Controller
{
    public function __construct ()
    {
        $this->middleware('auth');
    }

    public function profile ()
    {
        return view('users.profile');
    }

    public function showInformationsForm (Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $form = array_merge($user->only(['name', 'email']), $request->old());
        return view('users.informations', compact('form'));
    }

    public function updateInformations (UserInformationsRequest $request, UserRepository $userRepository)
    {
        $userRepository->update($request->user(), $request->only('name', 'email'));

        return redirect()->back()->with('success', __('User informations updated with success'));
    }

    public function showPasswordForm (Request $request)
    {
        return view('users.password');
    }

    public function updatePassword (UserPasswordRequest $request, UserRepository $userRepository, Hasher $hasher)
    {
        /** @var User $user */
        $user = $request->user();
        $oldPassword = $request->input('password');
        $newPassword = $request->input('new_password');

        if (!$hasher->check($oldPassword, $user->password)) {
            return redirect()
                ->back()
                ->withErrors(['password' => __('auth.failed')]);
        }

        $userRepository->update($user, ['password' => $newPassword]);

        $user->notify(new UserPasswordNotification());

        return redirect()
            ->back()
            ->with('success', __('User password updated with success'));
    }
}
