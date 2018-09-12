<?php

	namespace vendor\core;

class Db {

    use TSingleton;

	protected $pdo;

	public static $countSql = 0;
	public static $queries = [];

	protected function __construct() 
		{
			$db = require ROOT . '/config/config_db.php';
			$options = [
				\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
				\PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
			];
			$this->pdo = new \PDO($db['dsn'], $db['user'], $db['pass'], $options);
		}

	public function execute($sql, $params = []) {
		self::$countSql++;
		self::$queries[] = $sql;
		$stmt = $this->pdo->prepare($sql);
		return $stmt->execute($params);
	}

	public function query($sql, $params = []) {
		self::$countSql++;
		self::$queries[] = $sql;
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute($params);
		if($res !== false) {
			return $stmt->fetchAll();
		}
		return [];
	}
	public function count($sql, $params = []) {
		self::$countSql++;
		self::$queries[] = $sql;
		$stmt = $this->pdo->prepare($sql);
		$res = $stmt->execute($params);
		if($res !== false) {
			return $stmt->fetchColumn();
		}
		return [];
	}

	public function queryLast($sql, $params = []) {
		self::$countSql++;
		self::$queries[] = $sql;
		$stmt = $this->pdo->prepare($sql, array(\PDO::ATTR_CURSOR => \PDO::CURSOR_SCROLL));
		$res = $stmt->execute($params);
		if($res !== false) {
			return $stmt->fetch(\PDO::FETCH_ORI_FIRST);
		}
		return [];
	}
}
?>