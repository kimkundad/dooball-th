<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\User;
use Illuminate\Support\Facades\Hash;

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
    protected $redirectTo = '/home';
    
    protected $providers = [
        'github','facebook','google','twitter'
    ];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return 'username';
    }
    
    public function redirectTo(){
        // $role = Auth::user()->role;
        return '/game';
    }

    public function logout()
    {
        $role = Auth::user()->role; 

        Auth::logout();

        return redirect('/game');
    }

    public function checkLogin()
    {
        if (Auth::check()) {
            return response()->json(['total' => 1]);
        } else {
            return response()->json([]);
        }
    }

    public function show()
    {
        return view('auth.login');
    }

    public function redirectToProvider($driver)
    {
        if( ! $this->isProviderAllowed($driver) ) {
            return $this->sendFailedResponse("{$driver} is not currently supported");
        }

        try {
            return Socialite::driver($driver)->redirect();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }
    }
  
    public function handleProviderCallback( $driver )
    {
        try {
            $user = Socialite::driver($driver)->user();
        } catch (Exception $e) {
            return $this->sendFailedResponse($e->getMessage());
        }

        return $this->loginOrCreateAccount($user, $driver);
    }

    protected function sendSuccessResponse()
    {
        // return redirect()->intended('home');
        return redirect()->intended('game');
    }

    protected function sendFailedResponse($msg = null)
    {
        return redirect()->route('social.login')
            ->withErrors(['msg' => $msg ?: 'Unable to login, try with another provider to login.']);
    }

    protected function loginOrCreateAccount($providerUser, $driver)
    {
        // $email = $providerUser->getEmail();
        $providerId = $providerUser->id;

        $user = User::where('provider_id', $providerId)->first();

        if($user) {
            $user->update([
                'avatar' => $providerUser->avatar,
                'provider' => $driver,
                'access_token' => $providerUser->token
            ]);

            Auth::login($user, true);

            if ($user->username) {
                return $this->sendSuccessResponse();
            } else {
                $respDatas = array(
                    'screen_name' => $providerUser->getName(),
                    'avatar' => $providerUser->getAvatar(),
                    'user_id' => $user->id
                );

                return view('frontend/register-social', $respDatas);
            }
        } else {
            // create a new user
            $user = User::create([
                'role' => 'Member',
                'username' => '',
                'password' => '',
                'email' => $providerUser->getEmail(),
                'first_name' => '',
                'last_name' => '',
                'screen_name' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
                'line_id' => '',
                'tel' => '',
                'provider' => $driver,
                'provider_id' => $providerUser->getId(),
                'access_token' => $providerUser->token,
                'user_status' => 1
            ]);
    
            // login the user
            Auth::login($user, true);
    
            $respDatas = array(
                'screen_name' => $providerUser->getName(),
                'avatar' => $providerUser->getAvatar(),
                'user_id' => $user->id
            );
    
            return view('frontend/register-social', $respDatas);
        }

    }

    private function isProviderAllowed($driver)
    {
        return in_array($driver, $this->providers) && config()->has("services.{$driver}");
    }

    public function loginAfterSocial()
    {
        $user = User::where('username', 'NoiHealer')->first();
        $screen_name = '';
        $avatar = '';
        $id = 0;

        if ($user) {
            $screen_name = $user->screen_name;
            $avatar = $user->avatar;
            $id = $user->id;

            Auth::login($user, true);
        }

        $respDatas = array(
            'screen_name' => $screen_name,
            'avatar' => $avatar,
            'user_id' => $id
        );

        return view('frontend/register-social', $respDatas);
    }

    public function registerAfterSocial(Request $request)
    {
        $userId = $request->user_id;
        $username = trim($request->username);
        $user = User::where('id', $userId)->first();

        // if user already found
        if($user) {
            $user->update([
                'username' => $username,
                'screen_name' => trim($request->screen_name),
                'password' => Hash::make(trim($request->password)),
                'line_id' => trim($request->line_id),
                'tel' => trim($request->tel)
            ]);

            // login the user
            Auth::login($user, true);
            return $this->sendSuccessResponse();
        } else {
            return 'Update failed !';
        }
    }
}
