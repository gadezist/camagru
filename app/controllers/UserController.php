<?php

namespace app\controllers;


use app\models\User;
use vendor\core\Router;


class UserController extends AppController {

    

	public function signUpAction() {
	    $user = new User();
	    $data = $_POST;
        $user->load($data);
	    if(!$user->validate($data) || !$user->checkUnique()) {
            $user->getErrors();
            $_SESSION['form_data'] = $data;
            redirect();
        }
        $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
        $user->attributes['token'] = $user->createToken($user->attributes['email']);
        if($user->confirmEmail() && $user->save('users')) {
                $_SESSION['success'] = 'Thanks for signing up! Check your email for confirmation.';
        }else {
            $_SESSION['error'] = 'Error! Try again latter';
        }
        redirect('/user/index');
	}

	public function signInAction() {

        if(!empty($_POST)) {
            $user = new User();
            if($user->signin()){       
                    redirect('/');   
            }
        }
        redirect();
	}

    public function logoutAction() {
        if(isset($_SESSION['user'])) {
            unset($_SESSION['user']);
            unset($_SESSION['screens']);
        }
        redirect('/user/index');
    }

    public function indexAction() {
        $this->layout = 'user';

    }

    public function confirmAction() {
        $this->layout = 'user';
        $user = new User();
        if($user->checkToken($this->route)) {
            if($user->activate($this->route)) {
                $_SESSION['success'] = 'Congratylation! You are confirm email.';
                redirect('/user/index');
            }else {
                $_SESSION['error'] = 'error';
            }
        }else {
            $_SESSION['error'] = 'error';
        }
    }

    public function recoveryAction() {
        $this->layout = 'user';
    }

    public function continueAction() {

        $user = new User();
        $data = $_POST;
        $user->load($data);
        if(!$user->validateEmail($data)) {
            $_SESSION['error'] = "<span class='err'>email<span> is not a valid email address";
            redirect('/user/recovery');
        }
        if(!$user->checkEmailInDB($data)) {
            $_SESSION['error'] = 'email '  . $user->attributes['email'] . ' does not exist';
            redirect('/user/recovery');
        }
        $status = $user->checkActiveted();
        if($status == 'deactive') {
            $_SESSION['error'] = 'You are not activated your account. Please confirm your email address!';
            redirect('/user/index');
        }
        $user->attributes['token'] = $user->createToken($user->attributes['email']);
        if($user->confirmEmailForResetPassword($user->attributes['email'])) {
                $_SESSION['success'] = 'Check your email for confirmation.';
        }else {
            $_SESSION['error'] = 'Error! Try again latter';
        }
        redirect('/user/index');

    }

    public function resetAction() {
        $this->layout = 'user';
        $user = new User();
        $user->load($_POST);
        if($user->checkToken($this->route)) {
            $_SESSION['recovery']['token'] = $this->route['token'];
            $_SESSION['success'] = 'Please enter a new password';
        }else {
            redirect('/');
        }
    }

    public function resetPasswordAction() {
        $this->layout = 'user';
        $user = new User();
        $user->load($_POST);
        if(strlen($_POST['new_password']) >= 6) {
          if($_POST['new_password'] === $_POST['confirm_password']) {
                if($user->resetPassword()) {
                    $_SESSION['success'] = "Your password has been updated!";
                    redirect('/user/index');        
                }else {
                    $_SESSION['error'] = 'Error! Try again latter';
                }
            }else {
                $_SESSION['error'] = 'Passwords do not match! Try again';
                redirect('/user/reset/' . $_SESSION['recovery']['token']);
            }
        }else{
            $_SESSION['error'] = 'Passwords must be least 6 characters long';
            redirect('/user/reset/' . $_SESSION['recovery']['token']);
    }
}

    public function myaccountAction() {
        $this->layout = 'user';

    }

    public function saveAccountAction() {

        $data = $_POST;
        if(!empty($data)) {
            $user = new User();
            if(!$user->validateAccount($data) || !$user->checkUnique()) {
                $user->getErrors();
                redirect();
            }
            if(!empty($data['new_password']) && empty($data['old_password'])) {
                $_SESSION['error'] = 'Please write the old password';
            }
            if(!$user->saveDataAccount($data)) {
                $_SESSION['error'] = 'Error! Try again latter';
            }

        }
        redirect();
    }

    public function myimgAction() {
        $this->layout = 'user';
        $model = new User;
        $model->getImage();
    }

    public function removeImgAction() {
        if($this->isAjax()){
            $model = new User;
            $model->remove('screens');
        }
        die;
    }

}