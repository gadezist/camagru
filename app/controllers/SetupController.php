<?php
/**
 * Created by PhpStorm.
 * User: Andrii
 * Date: 14.07.2018
 * Time: 16:32
 */

namespace app\controllers;


class SetupController extends AppController
{

    public function indexAction()
    {
        if(!require_once ROOT . '/config/setup.php'){
        	redirect('/');
        }
    }
}