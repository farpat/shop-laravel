<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Bank\CartManager;
use Illuminate\Auth\SessionGuard;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';
    /**
     * @var CartManager
     */
    private $cartManager;

    /**
     * Create a new controller instance.
     *
     * @param CartManager $cartManager
     */
    public function __construct (CartManager $cartManager)
    {
        $this->middleware('guest')->except('logout');
        $this->cartManager = $cartManager;
    }

    /**
     * Show the application's login form.
     *
     * @param Request $request
     *
     * @return View
     */
    public function showLoginForm (Request $request, Session $session, UserRepository $userRepository)
    {
        $wantPurchase = $session->previousUrl() === route('cart.purchase');
        $users = $userRepository->getAll();
        return view('auth.login', compact('wantPurchase', 'users'));
    }

    public function spy (User $user, Request $request)
    {
        Auth::login($user, true);
        return $this->sendLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param User $user
     *
     * @return mixed
     */
    protected function authenticated (Request $request, $user)
    {
        $this->cartManager->refresh($user)->mergeItemsOnDatabase();

        if ($user->is_admin) {
            $this->redirectTo = route('user.profile');
        } else {
            $this->redirectTo = $request->input('purchase') ?
                route('cart.purchase') :
                route('home.index');
        }


        return redirect()
            ->intended($this->redirectPath())
            ->with('success', __("You are connected"));
    }

    /**
     * The user has logged out of the application.
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function loggedOut (Request $request)
    {
        return redirect()->route('home.index')->with('success', __('You are offline'));
    }
}
