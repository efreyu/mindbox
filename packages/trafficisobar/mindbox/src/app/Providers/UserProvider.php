<?php


namespace TrafficIsobar\Mindbox\app\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpKernel\Exception\HttpException;
use TrafficIsobar\Mindbox\app\Models\User;

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
        $userInfo['customer'] = [
            'email' => $credentials['username'],
            'password' => $credentials['password'],
        ];

        $response = \DirectCRM::sendRequest('Jti.v3.CustomerAuthentication', $userInfo);

        if ($response->isAuthenticated()) {
            $user = new User($credentials['username'], 'Mindbox UserName', app()->getLocale());
//            Cookie::queue('log', 'y', 60 * 60 * 24);
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
