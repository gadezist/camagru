<?php

namespace app\models;

use vendor\core\base\Model;

class Main extends Model {

	public $table = 'screens';
	public $table_l = 'likes';
	public $table_c = 'comments';

	public function saveImage($file) {

		$columns = "img_path, login, user_id";
		$values = "{$file}', '{$_SESSION['user']['login']}', '{$_SESSION['user']['id']}";
		$this->insert($this->table, $columns, $values);

	}

	public function getRandomFileName($path, $extention) {
		do {
			$name = uniqid();
			$file = $path . $name . $extention;
		} while(file_exists($file)) ;

		return $file; 
	}

	public function like() {
		$screen_id = $_POST['id'];
		$user_id = $_SESSION['user']['id'];
		$columns = "screen_id, user_id";
		$values = "{$screen_id}', '{$user_id}";
		$like = $this->findLike($this->table_l, $screen_id);
		if(empty($like)) {
			$this->insert($this->table_l, $columns, $values);
		}else {
			$keys = ['status', 'screen_id', 'user_id'];
			if($like['0']['status'] == 0) {
				$params = [1, $screen_id, $user_id];
				$this->updateLike('likes', $keys, $params);
			}else {
				$params = [0, $screen_id, $user_id];
				$this->updateLike('likes', $keys, $params);
			}
		}
		return $this->countLikes($this->table_l, [$screen_id]);
	}

	public function commentInsert() {
		$text = $_POST['text'];
		$screen_id = $_POST['screen_id'];
		$user_id = $_SESSION['user']['id'];
		$time = time();
		$columns = "user_id, screen_id, massage";
		$values = "{$user_id}', '{$screen_id}', '{$text}";
		$this->insert($this->table_c, $columns, $values);
	}

	public function getComments() {
		$screen_id = $_GET['screen_id'];
		$field = 'screen_id';
		$params = [$screen_id];
		return $this->findWhere($this->table_c, $field, $params);
	}

	public function getCommentLast() {
		$screen_id = $_POST['screen_id'];
		$field = 'screen_id';
		$params = [$screen_id];
		return $this->findLast($this->table_c, $field, $params);
	}

	public function upload() {
		$upload_dir = 'images/';
		$name = $_FILES['file']['name'];
		$tmp_name = $_FILES['file']['tmp_name'];
		if (move_uploaded_file($tmp_name, $upload_dir . $name)) {
			return $upload_dir . $name;
		} else {
			return 'error';
		}
	}

	public function sendEmail() {
		$header = require ROOT . '/config/email.php';
		$screen_id = $_POST['screen_id'];
		$field = 'id';
		$params = [$screen_id];
		$user = $this->getEmail($this->table, $field, $params);
		$email = $user[0]['email'];
		$login = $user[0]['login'];
		if(mail($email, 'Comment', "Dear $login! {$_SESSION['user']['login']} commented on your photo", $header)) {
             return true;
        }else {
            return false;
        }
	}

}