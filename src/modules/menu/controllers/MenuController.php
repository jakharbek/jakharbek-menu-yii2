<?php
namespace jakharbek\menu\modules\menu\controllers;

use Yii;
use yii\web\Controller;

class MenuController extends Controller{
    public function actions()
    {
        return [
            'manager' => 'jakharbek\menu\modules\menu\actions\menu\manager',
            'search' => 'jakharbek\menu\modules\menu\actions\menu\search',
            'index' => 'jakharbek\menu\modules\menu\actions\menu\search',
            'delete' => 'jakharbek\menu\modules\menu\actions\menu\delete',
        ];
    }
}