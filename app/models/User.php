<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 * Date: 15.07.2018
 * Time: 0:04
 */

namespace app\models;

use vendor\core\base\Model;

class User extends Model
{
	public $table = 'users';

    public $attributes = [
        'login' => '',
        'password' => '',
        'email' => '',
        'name' => '',
        'role' => 'user',
        'token' => '',
    ];

    public function checkUnique() {
    	$sql = "SELECT login, email FROM $this->table WHERE login = ? OR email = ? LIMIT 1";
    	$params = [$this->attributes['login'], $this->attributes['email']];
    	$user = $this->findBySql($sql, $params);
    	if($user) {
    		if($user[0]['login'] == $this->attributes['login']) {
    			$this->errors['loginExists'] = 'this login alredy exists';
    		}
    		if($user[0]['email'] == $this->attributes['email']) {
    			$this->errors['emailExists'] = 'this email alredy exists';
    		}
    		return false;
    	}
    	return true;
    }

    public function signin() {
    	$login = !empty(trim($_POST['login'])) ? trim($_POST['login']) : null;
    	$password = !empty(trim($_POST['password'])) ? trim($_POST['password']) : null;
    	if($login && $password) {
    		$sql = "SELECT * FROM $this->table WHERE login = ? LIMIT 1";
    		$params = [$login];
    		$user = $this->findBySql($sql, $params);
    		if($user) {
    			if(password_verify($password, $user[0]['password'])) {
    				if($user[0]['status'] == 'active') {
						$_SESSION['success'] = "You are successfully autorized";
    					foreach($user[0] as $key => $value) {
    						if($key != 'password') {
    							$_SESSION['user'][$key] = $value;
    						}
    					}
    					return true;
    				}else {
						$_SESSION['error'] = "Your account is not activated";
    				}
    			}else {
    				$_SESSION['error'] = "Login or password entered incorrectly";
    			}
    		}else {
                    $_SESSION['error'] = "Login or password entered incorrectly";
            }
    	}
    	return false;
    }

    public function confirmEmail() {
        $header = require ROOT . '/config/email.php';
        if(mail($this->attributes['email'], 'registration', 'Confirm: http://localhost:8100/user/confirm/' . $this->attributes['token'], $header)) {
                return true;
        }else {
                return false;
            }
    }

    public function checkEmailInDB($data) {
    	return $this->findOne($data['email'], 'email');
    }

    public function confirmEmailForResetPassword() {
    	$header = require ROOT . '/config/email.php';
    	$email = $this->attributes['email'];
    	$token = $this->attributes['token'];
        if(mail($email, 'recovery password', 'Confirm: http://localhost:8100/user/reset/' . $token, $header)) {
        	$params = [$token, $email];
            return $this->updateToken($this->table, $params);
        }else {
                return false;
            }
    }

    public function checkActiveted() {
    	$sql = "SELECT status FROM $this->table WHERE email=?";
    	$params=[$this->attributes['email']];
    	return $this->findBySql($sql, $params);
    }

    public function checkToken($route) {
    	$sql = "SELECT id FROM $this->table WHERE token = ?";
    	$params = [$route['token']];
    	return $this->findBySql($sql, $params);
    }

    public function activate($route) {
    	$params = [$route['token']];
    	return $this->updateActivate($this->table, $params);
    }

    public function resetPassword() {
    	$_SESSION['recovery']['new_password'] = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
    	$params = [$_SESSION['recovery']['new_password'], $_SESSION['recovery']['token']];
    	return $this->updatePassword($this->table, $params);
    }

    public function saveDataAccount($data) {
        debug($data);
        $login = trim($data['login']);
        $name = trim($data['name']);
        $email = trim($data['email']);
        $id = $_SESSION['user']['id'];
        if(isset($data['sendemail']) && $_SESSION['user']['sendemail'] == 0) {
            $params = [1, $id];
            if(!$this->updateDataAccount($this->table, $params, 'sendemail')) {
                return false;
            }
            $_SESSION['user']['sendemail'] = 1;
            $_SESSION['success'] = 'Data updated successfully';
        }else if(!isset($data['sendemail']) && $_SESSION['user']['sendemail'] == 1) {
            $params = [0, $id];
            if(!$this->updateDataAccount($this->table, $params, 'sendemail')) {
                return false;
            }
            $_SESSION['user']['sendemail'] = 0;
            $_SESSION['success'] = 'Data updated successfully';
        }
        if(!empty($login)) {
            $params = [$login, $id];
            if(!$this->updateDataAccount($this->table, $params, 'login')) {
                return false;
            }
            $_SESSION['user']['login'] = $login;
            $_SESSION['success'] = 'Data updated successfully';
        }
        if(!empty($name)) {
            $params = [$name, $id];
            if(!$this->updateDataAccount($this->table, $params, 'name')) {
                return false;
            }
            $_SESSION['user']['name'] = $name;
            $_SESSION['success'] = 'Data updated successfully';
        }
        if(!empty($email)) {
            $params = [$email, $id];
            if(!$this->updateDataAccount($this->table, $params, 'email')) {
                return false;
            }
            $_SESSION['user']['email'] = $email;
            $_SESSION['success'] = 'Data updated successfully';
        }
        if(!empty($data['old_password'])) {
            $sql = "SELECT * FROM $this->table WHERE id = ? LIMIT 1";
            $params = [$id];
            $user = $this->findBySql($sql, $params);
            if($user) {
                if(password_verify($data['old_password'], $user[0]['password'])) {
                    $password = password_hash($data['new_password'], PASSWORD_DEFAULT);
                    $params = [$password, $id];
                    if(!$this->updateDataAccount($this->table, $params, 'password')) {
                        return false;
                    }
                    $_SESSION['success'] = 'Data updated successfully';

                }else {
                    $_SESSION['error'] = 'Old password entered incorrectly';
                    redirect();
                }
            }
        }
        return true;
    }

    public function getImage() {
        $user = $_SESSION['user']['id'];
        $params = [$user];
        $sql = "SELECT * FROM screens WHERE user_id = ?";
        $screens = $this->findBySql($sql, $params);
        foreach($screens as $key => $value) {
            $_SESSION['screens'][$value['id']] = $value['img_path'];
        }

    }

    public function remove($table) {
            $id = $_POST['id'];
            $field = 'id';
            $params = [$id];
            if($this->delete($table, $field, $params)) {
                unset($_SESSION['screens']);
                echo 'ok';
            }
    }
}