<?php
namespace jakharbek\menu\models;
use jakharbek\menu\Bootstrap;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class MenuItems extends ActiveRecord{

    const SCENARIO_CREATE = "create";
    const SCENARIO_UPDATE = "update";

    public $query;
    public $menu_lang;
    public $menu_alias;

    public static function tableName()
    {
        return 'menu_items';
    }
    public function rules()
    {
        return [
            [['menu_id','title','url','extends','sort'],'required','on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
            [['title','url','extends'],'string','max' => '255','on' => [self::SCENARIO_CREATE,self::SCENARIO_UPDATE]],
        ];
    }

    public function scenarios()
    {
        $scenarious = parent::scenarios();
        $scenarious[self::SCENARIO_UPDATE] = ['title','url','extends','sort','menu_id','image','icon'];
        $scenarious[self::SCENARIO_CREATE] = ['title','url','extends','sort','menu_id','image','icon'];
        return $scenarious;
    }
    public function beforeValidate()
    {
        parent::beforeValidate();
        if(!self::findOne(['id' => $this->extends])){
            $this->extends = "none";
        }
        if(!($this->sort > 0)){
            $this->sort = 10000;
        }
        if($menu = Menu::getByAlias($this->menu_alias)){
            $this->menu_id = $menu->id;
        }
        return true;
    }

    public function getMenu(){
        return $this->hasOne(Menu::className(),['id' => 'menu_id']);
    }
    public function search(){
        $query = self::find();
        $query->joinWith('menu');
        $query->andFilterWhere(['like','menu_items.menu_id',$this->menu_id]);
        $query->andFilterWhere(['like','menu_items.title',$this->title]);
        $query->andFilterWhere(['like','menu_items.url',$this->url]);
        $query->andFilterWhere(['like','menu_items.extends',$this->extends]);
        $query->andFilterWhere(['like','menu_items.sort',$this->sort]);
        $query->andFilterWhere(['like','menu.lang',$this->menu_lang]);
        $query->andFilterWhere(['like','menu.alias',$this->menu_alias]);
        $query->orderBy('menu_items.sort ASC');
        $this->query = $query;
        return $this;
    }

    public static function getItems($menu_alias = null,$extends = "none",$lang = 'default'){
        if($menu_alias == null){return false;}
        if($lang == "default"){$lang = Yii::$app->language;}
        $menuItems = new self();
        $menuItems->menu_alias = $menu_alias;
        $menuItems->extends = $extends;
        $menuItems->menu_lang = $lang;
        $menuItems->search();
        if($menuItems->query->count() == 0){return false;}
        return $menuItems->query;
    }
    public function deleteItem(){
        $id = $this->id;
        $image = $this->image;
        if($this->delete()):
            if(strlen($image) == 0){return true;}
            if(file_exists(Bootstrap::$upload_folder.$image)):
                unlink(Bootstrap::$upload_folder.$image);
            endif;
            $all_extends = self::findAll(['extends' => $id]);
            if(count($all_extends)):
                foreach ($all_extends as $extend):
                    $extend->deleteItem();
                endforeach;
            endif;
            return true;
        else:
            return false;
        endif;
    }
    public function getImageLink(){
        return Bootstrap::$upload_folder_source.$this->image;
    }
    public function attributeLabels(){
        return [
            'id' => Yii::t('jakhar-menu','Unique ID'),
            'menu_id' => Yii::t('jakhar-menu','Menu ID'),
            'title' => Yii::t('jakhar-menu','Title'),
            'url' => Yii::t('jakhar-menu','Url'),
            'extends' => Yii::t('jakhar-menu','Extends'),
            'sort' => Yii::t('jakhar-menu','Sort'),
            'icon' => Yii::t('jakhar-menu','Icon'),
            'image' => Yii::t('jakhar-menu','Image'),
            'imageFile' => Yii::t('jakhar-menu','imageFile'),
        ];
    }
}