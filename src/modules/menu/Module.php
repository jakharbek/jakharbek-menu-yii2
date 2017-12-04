<?php

namespace jakharbek\menu\modules\menu;
use Yii;
use jakharbek\menu\Bootstrap;
use yii\filters\AccessControl;
/**
 * test module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'jakharbek\menu\modules\menu\controllers';
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Bootstrap::$premission_admin_panel],
                    ],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}
