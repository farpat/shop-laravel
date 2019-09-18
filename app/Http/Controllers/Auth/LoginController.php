<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Repositories\CartRepository;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Session\Session;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

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
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * Create a new controller instance.
     *
     */
    public function __construct ()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Show the application's login form.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function showLoginForm (Request $request, Session $session, UserRepository $userRepository)
    {
        $wantPurchase = $session->previousUrl() === route('cart.purchase');
        $users = $userRepository->getAll();
        return view('auth.login', compact('wantPurchase', 'users'));
    }

    public function spy (User $user, Request $request)
    {
        Auth::login($user);
        return $this->sendLoginResponse($request);
    }

    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param mixed $user
     *
     * @return mixed
     */
    protected function authenticated (Request $request, $user)
    {
        $cartRepository = app(CartRepository::class);

        $cartRepository->mergeItemsOnDatabase(collect($cartRepository->getCookieItems()));

        if ($request->input('purchase')) {
            $this->redirectTo = route('cart.purchase');
        }

        return redirect()->intended($this->redirectPath());
    }
}
