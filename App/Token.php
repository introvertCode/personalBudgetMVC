<?php

namespace App;

/**
 * Unique random tokens
 */
class Token
{
    /**
     * The token value
     * @var string
     */
    protected $token;

    /**
     * Class contructor. create a new random token
     * @return void
     */
    public function __construct($token_value = null){
        if($token_value){
            $this->token = $token_value;
        } else{
        $this->token = bin2hex(random_bytes(16)); // 16bytes = 128 bits = 32 hex characters
        }
    }
    /**
     * Get th etoken value
     * @return string The value
     */
    public function getValue(){
        return $this->token;
    }

    /**
     * Get th ehashed token value
     * @return string the hashed value
     */
    public function getHash(){
        return hash_hmac('sha256', $this->token, \App\Config::SECRET_KEY);
    }
}