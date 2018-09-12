<?php

namespace vendor\libs;


class Validator {

	public function required($data) {
		$errors = [];
		foreach($data as $key => $value) {
			if(empty(trim($value))) {
				$this->errors[] = $key;
			}
		}
		if(empty($this->errors)) {
			return true;
		}else {
			return $this->errors;
		}
	}

	public function minLengthLogin($data) {
		if(strlen(trim($data)) < 3) {
			return false;
		}else {
			return true;
		}
	}

	public function minLengthPass($data) {
		if(strlen($data) < 6) {
			return false;
		}else {
			return true;
		}


	}

	public function email($email) {
		if(filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}else {
			return false;
		}
	}


}