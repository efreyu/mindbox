<?php


namespace TrafficIsobar\Mindbox\app\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider as IlluminateUserProvider;
use Illuminate\Contracts\Session\Session;
use TrafficIsobar\Mindbox\app\Models\User;

class UserProvider implements IlluminateUserProvider
{
    private const SESSION_KEY_USER = 'user';

    /**
     * The session used by the guard.
     *
     * @var Illuminate\Contracts\Session\Session
     */
    protected $session;


    /**
     * UserProvider constructor.
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param mixed $identifier
     * @return Authenticatable|mixed|null
     */
    public function retrieveById($identifier)
    {
        $user = $this->session->get(self::SESSION_KEY_USER);
        if (empty($user) || ! $user instanceof User || $user->getUsername() != $identifier) {
            return null;
        }
        return $user;
    }

    /**
     * @param array $credentials
     * @return Authenticatable|User|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $user = $this->apiAuthUser($credentials);
        if (is_object($user)) {
            $this->updateSession($user);
        }
        return $user;
    }


    /**
     * @param $credentials
     * @return User|null
     */
    private function apiAuthUser($credentials)
    {
        $userInfo['customer'] = [
            'email' => $credentials['username'],
            'password' => $credentials['password'],
        ];

        $response = \DirectCRM::sendRequest('Jti.v3.CustomerAuthentication', $userInfo);

        if ($response->isAuthenticated() && $id = $response->get('customer', 'ids', 'mindboxId')) {
            return new User($id, $credentials['username'], 'Agency3', app()->getLocale());
        }

        return null;
    }

    /**
     * @param Authenticatable $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        return true;
    }

    /**
     * @param mixed $identifier
     * @param string $token
     * @return Authenticatable|void|null
     */
    public function retrieveByToken($identifier, $token)
    {
        //
    }

    /**
     * @param Authenticatable $user
     * @param string $token
     */
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
