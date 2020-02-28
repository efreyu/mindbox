<?php

namespace TrafficIsobar\Mindbox\app\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    protected $username;
    protected $email;
    protected $lang;

    /**
     * User constructor.
     * @param $email
     * @param $username
     * @param $lang
     */
    public function __construct($email, $username, $lang)
    {
        $this->email = $email;
        $this->username = $username;
        $this->lang = $lang;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return mixed
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    /**
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }


    public function getAuthPassword()
    {
        // Returns the (hashed) password for the user
    }

    /**
     * @return string
     */
    public function getRememberToken()
    {
        // Return the token used for the "remember me" functionality
    }

    /**
     * @param  string $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Store a new token user for the "remember me" functionality
    }

    /**
     * @return string
     */
    public function getRememberTokenName()
    {
        // Return the name of the column / attribute used to store the "remember me" token
    }
}
