<?php
namespace jakharbek\menu\modules\menu\actions\menu;

use jakharbek\menu\models\Menu;
use jakharbek\menu\models\MenuForm;
use jakharbek\menu\models\MenuItems;
use jakharbek\menu\models\MenuItemsForm;
use Yii;
use yii\base\Action;
use yii\web\Response;
use yii\widgets\ActiveForm;
use yii\web\UploadedFile;

class manager extends Action{

    public $view = '@jakhar/menu/modules/menu/views/menu/manager';

    public function run($alias = null,$lang = null){

        if($lang !== null){Yii::$app->language = $lang;}

        //MENU MENU

        $model = new MenuForm(['scenario' => Menu::SCENARIO_CREATE,'alias' => $alias,'lang' => $lang]);

        if(($db = Menu::getByAlias($alias,$lang)))
        {
            $db->scenario = Menu::SCENARIO_UPDATE;
            $model->scenario = Menu::SCENARIO_UPDATE;
        }
        else
        {
            $db = new Menu(['scenario' => Menu::SCENARIO_CREATE]);
        }

        //ajax logic
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        //logic
        if ($model->load(Yii::$app->request->post()))
        {
            if($model->validate())
            {
                $db->attributes = $model->attributes;
                if($db->save())
                {
                    $is_save = true;
                    $alias = $db->alias;
                }
            }
        }
        if($model->scenario == Menu::SCENARIO_CREATE){
            $submit_text = Yii::t('jakhar-menu','Menu Create');
        }elseif($model->scenario == Menu::SCENARIO_UPDATE){
            $submit_text = Yii::t('jakhar-menu','Menu Update');
        }


        if(Yii::$app->request->isAjax)
        {

            if(Yii::$app->request->post("menuItemDelete")):
                $menuItemId = Yii::$app->request->post("id");
                $item = MenuItems::findOne(['id' => $menuItemId]);
                if($item->deleteItem()){
                    echo "ok";
                }
                exit();
            endif;

            if(Yii::$app->request->post("menuItemUpdate")):
                $menuItemId = Yii::$app->request->post("id");
                $menuItemSort = Yii::$app->request->post("sort");
                $menuItemParent = intval(Yii::$app->request->post("parent"));
                $menuItemParent = ($menuItemParent == 0) ? "none" : $menuItemParent;

                $item = MenuItems::findOne(['id' => $menuItemId]);
                $item->sort = $menuItemSort;
                $item->extends = $menuItemParent;
                if($item->save()){
                    echo "ok";
                    exit();
                }
            endif;
        }

        $session = Yii::$app->session;

        //MENU ITEM CREATE
        $modelItemCreate = new MenuItemsForm(['scenario' => MenuItems::SCENARIO_CREATE]);
        //ajax logic
        if (Yii::$app->request->isAjax && $modelItemCreate->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if($modelItemCreate->load(Yii::$app->request->post())):
            $modelItemCreate->imageFile = UploadedFile::getInstance($modelItemCreate, 'imageFile');
            if($modelItemCreate->validate() && $modelItemCreate->upload()):
                $menuItemDbCreate = new MenuItems(['scenario' => MenuItems::SCENARIO_CREATE]);
                $menuItemDbCreate->attributes = $modelItemCreate->attributes;
                $menuItemDbCreate->menu_alias = $alias;
                $menuItemDbCreate->save();
            endif;
        endif;


        //MENU ITEM UPDATE

        $modelItemUpdate = new MenuItemsForm(['scenario' => MenuItems::SCENARIO_UPDATE]);
        //ajax logic
        if (Yii::$app->request->isAjax && $modelItemUpdate->load(Yii::$app->request->post())){
            Yii::$app->response->format = Response::FORMAT_JSON;
        }

        if($modelItemUpdate->load(Yii::$app->request->post())):
            $modelItemUpdate->imageFile = UploadedFile::getInstance($modelItemUpdate, 'imageFile');
            if($modelItemUpdate->validate() && $modelItemUpdate->upload()):
                $menuItemDb = MenuItems::findOne(['id' => $modelItemUpdate->id]);
                $menuItemDb->title = $modelItemUpdate->title;
                $menuItemDb->url = $modelItemUpdate->url;
                $menuItemDb->icon = $modelItemUpdate->icon;
                $menuItemDb->image = $modelItemUpdate->image;
                $menuItemDb->save();
            endif;
        endif;

        $all_menus = Menu::findAll(['lang' => Yii::$app->language]);

        return $this->controller->render($this->view,compact(['model','db','session','lang','alias','is_save','submit_text','modelItemCreate','all_menus']));
    }
}