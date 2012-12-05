<?php

/**
 * Panada Auth Library.
 * 
 * @package	Libraries
 * @link	http://panadaframework.com/
 * @license     http://www.opensource.org/licenses/bsd-license.php
 * @author	Harry Sudana <harrysudana@gmail.com>
 * @since	Version 0.1
 */

namespace Libraries;
use Resources;

class Auth {

    private $authconf;

    public function __construct() {
        $this->authconf = \Resources\Config::auth();
        $this->db = new \Resources\Database;
        $this->session = new \Resources\Session;
    }

    public function login($username, $password, $remember = FALSE) {
        if (empty($password))
            return FALSE;

        return $this->_login($username, $password, $remember);
    }

    protected function _login($user, $password, $remember) {
        if (!is_object($user)) {
            $username = $user;
            // Load the user
            $user = $this->db->getOne('users', array( 'username' => $username) );
            
        }

        if (is_string($password)) {
            // Create a hashed password
            $password = $this->hash($password);
        }
        
        $role = $this->db->getOne('roles', array( 'name' => 'login') );
        
        $hasrole = $this->db->getOne('role_users', array( 'role_id', $role->id, 'user_id', $user->id ) );

        if ($hasrole AND $user->password === $password) {
            $data = array(
                'id' => $user->id,
                'username' => $user->username,
                'password' => $user->password,
                'email' => $user->email,
                'updated_at' => $user->updated_at,
                'created_at' => $user->created_at,
            );
            $this->complete_login($data);
            return TRUE;
        }
        // Login failed
        return FALSE;
    }

    public function hash($str) {
        if (!$this->authconf['hash_key'])
            throw new Exception('A valid hash key must be set in your auth config.');

        return hash_hmac($this->authconf['hash_method'], $str, $this->authconf['hash_key']);
    }
    
    protected function complete_login($user) {
        // Regenerate session_id
        //$this->session->regenerate();
        // Store username in session
        //$this->session->set($this->config->auth->session_key, $user);
        $this->session->setValue($user);

        return TRUE;
    }
    
    public function generateSignature(){
        $signature = md5( uniqid(rand(), true) );
        $this->session->setValue( 'loginSignature', $signature );
        return $signature;
    }
    
    public function validateSignature($signature){
        if($signature == $this->session->getValue( 'loginSignature') )
            return true;
        return false;
    }
    
}