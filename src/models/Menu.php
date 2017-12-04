<?php
namespace jakharbek\menu\models;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\Url;
use yii\web\IdentityInterface;

class Menu extends ActiveRecord{

    const SCENARIO_CREATE = "create";
    const SCENARIO_UPDATE = "update";
    const SCENARIO_SEARCH = "search";

    public $query;

    public static function tableName()
    {
        return 'menu';
    }
    public function rules()
    {
        return [
            [['alias','title','lang'],'required','on' => self::SCENARIO_CREATE],
            [['alias','title','lang'],'string','max' => '255','on' => self::SCENARIO_CREATE],


            [['alias','title','lang'],'required','on' => self::SCENARIO_UPDATE],
            [['alias','title','lang'],'string','max' => '255','on' => self::SCENARIO_UPDATE],

            [['alias','title','lang'],'safe','on' => self::SCENARIO_SEARCH],
        ];
    }

    public function scenarios()
    {
        $scenarious = parent::scenarios();
        $scenarious[Menu::SCENARIO_CREATE] = ['title','alias','lang'];
        $scenarious[Menu::SCENARIO_UPDATE] = ['title','alias','lang'];
        $scenarious[Menu::SCENARIO_SEARCH] = ['title','alias','lang'];
        return $scenarious;
    }
    public function getMenu_items(){
       return $this->hasMany(MenuItems::className(),['menu_id' => 'id']);
    }
    public static function getByAlias($alias = null,$lang = null){
        if($alias == null){return false;}
        if($lang == null){$lang = Yii::$app->language;}
        return self::findOne(['alias' => $alias,'lang' => $lang]);
    }

    public function search(){
        $query = self::find();
        $this->lang = Yii::$app->language;
        $query->andFilterWhere(['like','title',$this->title]);
        $query->andFilterWhere(['like','alias',$this->alias]);
        $query->andFilterWhere(['like','lang',$this->lang,false]);
        $this->query = $query;
        return $query;
    }
    public static function getById($id = null){
        if($id == null){return false;}
        return self::findOne(['id' => $id]);
    }
    public function attributeLabels(){
        return [
            'id' => Yii::t('jakhar-menu','Unique ID'),
            'title' => Yii::t('jakhar-menu','Title'),
            'alias' => Yii::t('jakhar-menu','Alias'),
            'lang' => Yii::t('jakhar-menu','Lang'),
        ];
    }

}