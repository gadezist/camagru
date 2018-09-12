<?php

namespace vendor\core\base;

use vendor\core\Db;
use vendor\libs\Validator;

abstract class Model {

	protected $pdo;
	protected $table;
	protected $pk = 'id';
	public $attributes = [];
	public $errors = [];
	public $rules = [];
    public $scr_id = 'screen_id';
    public $user_id = 'user_id';

	public function __construct() {
		$this->pdo = Db::instance();
	}

	public function load($data) {
        foreach($this->attributes as $name => $value) {
            if(isset($data[$name])) {
                $this->attributes[$name] = trim($data[$name]);
            }
        }
    }

    public function validate($data) {
    	$valid = new Validator();

    	$required = $valid->required(['login' => $data['login'], 'password' => $data['password'], 'email' => $data['email'], 'name' => $data['name']]);
    	if($required !== true) {
    		foreach($required as $key)
    		$this->errors[] = "<span class='err'>$key</span> is required";
    	}
    	if(!$valid->minLengthLogin($data['login'])) {
    		$this->errors[] = "<span class='err'>login</span> must be least 3 characters long";
    	}
    	if(!$valid->minLengthPass($data['password'])) {
    		$this->errors[] = "<span class='err'>password</span> must be least 6 characters long";
    	}
    	if(!$valid->email($data['email'])) {
    		$this->errors[] = "<span class='err'>email<span> is not a valid email address";
    	}
    	if(empty($this->errors)) {
    		return true;
    	}else {
    		return false;
    	}
    	
    }

    public function validateAccount($data) {
        $valid = new Validator();

        if(!empty(trim($data['login']))) {
            if(!$valid->minLengthLogin($data['login'])) {
                $this->errors[] = "<span class='err'>login</span> must be least 3 characters long";
            }
        }

        if(!$valid->minLengthPass($data['new_password']) && !empty($data['old_password'])) {
            $this->errors[] = "<span class='err'>password</span> must be least 6 characters long";
        }
        if(!empty(trim($data['email']))) {
            if(!$valid->email($data['email'])) {
                $this->errors[] = "<span class='err'>email<span> is not a valid email address";
            }
        }
        if(empty($this->errors)) {
            return true;
        }else {
            return false;
        }

    }

    public function validateEmail($data) {
    	$valid = new Validator();

    	if(!$valid->email($data['email'])) {
    		return false;
    	}else {
    		return true;
    	}
    }

    public function save($table) {
    	$columnStr = array_keys($this->attributes);
    	$columnStr = implode(", ", $columnStr);
    	$valueStr = implode("', '", $this->attributes);
    	return ($this->insert($table, $columnStr, $valueStr));


    }

    public function getErrors() {
    	$errors = '<ul>';
    	foreach($this->errors as $error) {
    		$errors .= "<li><span>$error</span></li>";
    	}

    	$errors .= '</ul>';
    	$_SESSION['error'] = $errors;
    }


	public function query($sql) {
		return $this->pdo->execute($sql);
	}

	public function findAll() {
		$sql = "SELECT * FROM {$this->table}";
		return $this->pdo->query($sql);
	}

    public function findWhere($table, $field, $params) {
        $sql = "SELECT massage, date_massage, login FROM {$table} JOIN users ON comments.user_id=users.id WHERE $field = ? ORDER BY date_massage";
        return $this->pdo->query($sql, $params);
    }

    public function findLimit($start, $limit) {
        $sql = "SELECT screens.id, screens.img_path, screens.login, screens.user_id, COUNT(likes.status) AS count FROM screens LEFT JOIN likes ON screens.id=likes.screen_id AND status='1'GROUP BY screens.id LIMIT $start, $limit";
        return $this->pdo->query($sql);
    }

	public function findOne($id, $field = '') {
		$field = $field ?: $this->pk;
		$sql = "SELECT * FROM {$this->table} WHERE $field = ? LIMIT 1";
		return $this->pdo->query($sql, [$id]);
	}

    public function findLast($table, $field, $params) {
        $sql = "SELECT massage, date_massage, login FROM {$table} JOIN users ON comments.user_id=users.id WHERE $field = ? ORDER BY date_massage DESC";
        return $this->pdo->queryLast($sql, $params);
    }
    public function findLike($table, $screen_id) {
        $sql = "SELECT * FROM $table WHERE $this->scr_id = ? AND $this->user_id = ? LIMIT 1";
        $params = [$screen_id, $_SESSION['user']['id']];
        return $this->pdo->query($sql, $params);
    }

	public function findBySql($sql, $params = []) {
		return $this->pdo->query($sql, $params);
	}

/*	public function findLike($str, $find, $table = '') {
		$table = $table ?: $this->table;
		$sql = "SELECT * FROM $table WHERE $field LIKE ?";
		return $this->pdo->query($sql, ['%' . $str . '%']);
	}*/

	public function insert($table, $columns = '', $value = '') {
		$sql = "INSERT INTO $table ($columns) VALUES ('$value')";
		return $this->pdo->execute($sql);
	}

	public function updateActivate($table, $params) {
		$sql = "UPDATE $table SET status='active', token='' WHERE token=?";
		return $this->pdo->execute($sql, $params);
	}

	public function updatePassword($table, $params) {
		$sql = "UPDATE $table SET password=?, token='' WHERE token=?";
		return $this->pdo->execute($sql, $params);
	}

	public function updateToken($table, $params) {
		$sql = "UPDATE $table SET token=? WHERE email=?";
		return $this->pdo->execute($sql, $params);
	}

    public function updateDataAccount($table, $params, $key) {
        $sql = "UPDATE $table SET $key=? WHERE id=?";
        return $this->pdo->execute($sql, $params);
    }

    public function updateLike($table, $keys, $params) {
        $sql = "UPDATE $table SET $keys[0]=? WHERE $keys[1]=? AND $keys[2]=?";
        return $this->pdo->execute($sql, $params);
    }

	public function createToken($email) {
		return md5($email.time());
	}
    public function countLine() {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        return $this->pdo->count($sql);
    }

    public function countLikes($table, $params) {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE $this->scr_id=? AND status='1'";
        return $this->pdo->count($sql, $params);
    }

    public function getEmail($table, $field, $params) {
        $sql = "SELECT users.email, users.login FROM $table JOIN users ON {$table}.user_id=users.id WHERE $table.$field = ?";
        return $this->pdo->query($sql, $params);
    }

    public function delete($table, $field, $params) {
        $sql = "DELETE FROM $table WHERE $field = ?";
        return $this->pdo->execute($sql, $params);
    }
}