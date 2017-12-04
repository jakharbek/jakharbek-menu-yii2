<?php
namespace jakharbek\menu\modules\menu\actions\menu;

use jakharbek\menu\Bootstrap;
use jakharbek\menu\models\MenuItems;
use Yii;
use yii\base\Action;
use jakharbek\menu\models\Menu;
use jakharbek\menu\models\MenuForm;
use yii\helpers\Url;
use yii\web\Response;

class delete extends Action{
    public function run($id = null){
        $session = Yii::$app->session;
        //init
        if (Yii::$app->request->isAjax){
            Yii::$app->response->format = Response::FORMAT_JSON;
            $id = Yii::$app->request->post('id');
            echo "ok";
        }
        if(($menu = Menu::getById($id))){
            $menus = Menu::findAll(['alias' => $menu->alias]);
            if(count($menus)):
                foreach ($menus as $menu):
                    $items = MenuItems::findAll(['menu_id' => $menu->id]);
                    if(count($items)):
                        foreach ($items as $item):
                            $item->deleteItem();
                        endforeach;
                    endif;
                    $menu->delete();
                endforeach;
            endif;

        }
        if (!Yii::$app->request->isAjax) {
            $this->controller->redirect(Url::to([Bootstrap::$pages['search']]));
        }
    }
}