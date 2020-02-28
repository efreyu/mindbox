<?php

namespace TrafficIsobar\Mindbox\app\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    protected $id;
    protected $username;
    protected $email;
    protected $auth;
    protected $level;
    protected $lang;

    public function __construct($id, $username, $email, $auth, $level, $lang)
    {
        $this->id = $id;
        $this->username = $username;
        $this->email = $email;
        $this->auth = $auth;
        $this->level = $level;
        $this->lang = $lang;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getAuth()
    {
        return $this->auth;
    }

    public function getLevel()
    {
        return $this->level;
    }

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
