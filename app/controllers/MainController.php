<?php

namespace app\controllers;

use app\models\Main;
use vendor\core\App;
use vendor\core\base\View;
use vendor\libs\Pagination;

class MainController extends AppController {

	public $perpage = 9;
	
	public function indexAction() {

		$model = new Main;

		$total = $model->countLine();
		$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
		$pagination = new Pagination($page, $this->perpage, $total);
		$start = $pagination->getStart();
		$screens = $model->findLimit($start, $this->perpage);
	
		View::setMeta('Main page', 'description', 'keywords');
		$this->set(compact('screens', 'pagination'));
	}

	public function saveImgAction() {
		if($this->isAjax()) {
			$img = str_replace(' ', '+', $_POST['img']);
			$img = base64_decode($img);
			$model = new Main;
			$path = 'screens/';
			$extention = '.png';
			$fileName = $model->getRandomFileName($path, $extention);
			file_put_contents($fileName, $img);
			$model->saveImage('web/' . $fileName);
			$screens = $model->findAll();

			$total = $model->countLine();
			$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
			$pagination = new Pagination($page, $this->perpage, $total);
			$start = $pagination->getStart();
			$screens = $model->findLimit($start, $this->perpage);
			$this->loadView('index', compact('screens', 'pagination'));
			die;
		}
	}

	public function likeAction() {

		if(!isset($_SESSION['user'])) {
			echo ('false');
		} else {
			if($this->isAjax()) {
				$model = new Main;
				echo $model->like();
			}
		}
		die;
	}

	public function saveCommentAction() {
			if($this->isAjax()) {
				$model = new Main;
				if($_SESSION['user']['sendemail'] == '1') {
					$model->sendEmail();
				}
				$model->commentInsert();
				$comment = $model->getCommentLast();
				$json = json_encode($comment);
				echo $json;
			}
		die;
	}

	public function commentsAction() {
		if(!isset($_SESSION['user'])) {
			echo ('false');
		}else if($this->isAjax()) {
			$model = new Main;
			$comments = $model->getComments();
			$json = json_encode($comments);
			echo $json;
		}
		die;
	}

	public function uploadFileAction() {
		if($this->isAjax()) {
			$model = new Main;
			echo $model->upload();
		die;
		}
	}
}