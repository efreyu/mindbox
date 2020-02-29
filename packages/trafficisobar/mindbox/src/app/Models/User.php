<?php

namespace TrafficIsobar\Mindbox\app\Models;

use Illuminate\Contracts\Auth\Authenticatable;

class User implements Authenticatable
{
    protected $id;
    protected $username;
    protected $email;
    protected $lang;

    /**
     * User constructor.
     * @param $mindboxId int
     * @param $email string
     * @param $fakeUserName string
     * @param $lang string
     */
    public function __construct($mindboxId, $email, $fakeUserName, $lang)
    {
        $this->id = $mindboxId;
        $this->email = $email;
        $this->username = $fakeUserName;
        $this->lang = $lang;
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Returns the username. This is a fake name because mindbox will not send the original username.
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @return string
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
     * @return string
     */
    public function getAuthIdentifier()
    {
        return $this->{$this->getAuthIdentifierName()};
    }


    /**
     * @return void
     */
    public function getAuthPassword()
    {
        // Returns the (hashed) password for the user
    }

    /**
     * @return void
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
     * @return void
     */
    public function getRememberTokenName()
    {
        // Return the name of the column / attribute used to store the "remember me" token
    }
}
