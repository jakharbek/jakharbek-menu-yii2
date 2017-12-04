<?php
namespace jakharbek\menu\modules\menu\actions\menu;

use jakharbek\menu\models\Menu;
use jakharbek\menu\models\MenuForm;
use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use \jakharbek\user\models\User;
use \jakharbek\user\models\UserForm;
use \jakharbek\core\token\models\Token;
use yii\Helpers\Url;
use yii\Helpers\Html;
use jakharbek\user\Bootstrap;

class search extends Action{

    public $view = '@jakhar/menu/modules/menu/views/menu/search';

    public function run(){
        $session = Yii::$app->session;

        $menu = new Menu(['scenario' => Menu::SCENARIO_SEARCH]);
        $model = new MenuForm(['scenario' => Menu::SCENARIO_SEARCH]);

        $model->load(Yii::$app->request->get());
        $menu->attributes = $model->attributes;
        $menu->search();
        $query = $menu->query;

        $adprovider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'title' => SORT_ASC,
                ],
            ],
        ]);
        //init
        return $this->controller->render($this->view,compact('adprovider','model','menu','session'));
    }
}