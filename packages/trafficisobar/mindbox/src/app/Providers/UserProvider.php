<?php


namespace TrafficIsobar\Mindbox\app\Providers;

use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserProvider implements IlluminateUserProvider
{
    const SESSION_KEY_USER = 'user';

    /**
     * The session used by the guard.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;


    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function retrieveById($identifier)
    {
        $user = $this->session->get(self::SESSION_KEY_USER);
        if (empty($user) || ! $user instanceof User || $user->getUsername() != $identifier) {
            return null;
        }
        return $user;
    }

    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->apiAuthUser($credentials);
        if (is_object($user)) {
            $this->updateSession($user);
        }
        return $user;
    }


    private function apiAuthUser($credentials)
    {
//        $manager = new BillingManager();
        dd($credentials);
        $userInfo['customer'] = [
            'email' => $credentials['email'],
            'password' => $credentials['password'],
        ];
        $response = \DirectCRM::sendRequest('Jti.v3.CustomerAuthentication', $userInfo);
        if ($response->isAuthenticated()) {
            $credentials = [
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'lang' => app()->getLocale()
            ];
        }
        if (isset($credentials['key'])) {
            // сквозная авторизация
            try{  $response = $manager->query('auth', [
                'username' => $credentials['username'],
                'key' => $credentials['key'],
                'checkcookie' => 'no',
                'lang' => app()->getLocale()
            ]);
            } catch ( \Exception $e){
                throw new HttpException(403,'key auth not valid',$e);
            }
        } else {
            $response = $manager->query('auth', [
                'username' => $credentials['username'],
                'password' => $credentials['password'],
                'lang' => app()->getLocale()
            ]);
        }

        if ($response instanceof SuccessResponse) {
            $response = $response->getResponse();
            $user = new User($response['doc']['auth']['$userid'], $response['doc']['tparams']['username']['$'], $response['doc']['auth']['$email'], $response['doc']['auth']['$id'], $response['doc']['auth']['$level'], $response['doc']['$lang']);

            Cookie::queue('log', 'y', 60 * 60 * 24);

            return $user;
        }

        return null;
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    public function retrieveByToken($identifier, $token)
    {
        //
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        //
    }

    /**
     * Update the session with the given ID.
     *
     * @param User $user
     * @return void
     */
    protected function updateSession(User $user)
    {
        $this->session->put(self::SESSION_KEY_USER, $user);
        $this->session->save();
    }
}
