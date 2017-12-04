Menu
====
Menu yii2

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jakharbek/jakharbek-menu "*"
```

or add

```
"jakharbek/jakharbek-menu": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, simply use it in your code by  :

You must migrate the database from the / migration folder

Once the extension is installed, you mast configurate extension; To do this, you need to open the src/bootstrap.php:

You need to register the module in the system

for register module in system  'moduleName';
for register module in module parentModuleName/moduleName;
 public static $modules = ['test/menu' => 'jakharbek\menu\modules\menu\Module'];
 
 You need to install the download folder
 
 self::$upload_folder = Yii::getAlias("@webroot")."/uploads/menu/"; 
 self::$upload_folder_source = Yii::getAlias("@web")."/uploads/menu/";

 You need to provide links to pages:
 
 public static $pages = [
        'search' => '/test/menu/menu/search',
        'manager' => '/test/menu/menu/manager',
        'delete' => '/test/menu/menu/delete'
        ];
		
To indicate its permission to access the admin to change:		
		
public static $premission_admin_panel = "controlPanel";

For layout of the menu for your site, you need to inherit the ManuRender class and implement its methods and call where you want to map the example menu to the MenoTop classes located in the folders components

