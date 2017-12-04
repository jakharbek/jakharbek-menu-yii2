<?php

use yii\db\Migration;

class m171123_154416_menu extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }
        //menu
        $this->createTable('menu', [
            'id' => $this->primaryKey()->unique()->comment("Уникальный порядковый номер"),
            'alias' => $this->string(255)->notNull()->comment("Алиас"),
            'title' => $this->string(255)->notNull()->comment("Название"),
            'lang' => $this->string(255)->notNull()->comment("Язык"),
        ]);

        //MenuItems
        $this->createTable('menu_items', [
            'id' => $this->primaryKey()->unique()->comment("Уникальный порядковый номер"),
            'menu_id' => $this->integer(255)->notNull()->comment("Меню"),
            'title' => $this->string(255)->notNull()->comment("Название"),
            'url' => $this->text()->null()->comment("Ссылка"),
            'extends' => $this->string(255)->null()->comment("Расширает"),
            'sort' => $this->integer(255)->null()->comment("Сортировка"),
            'icon' => $this->string(255)->null()->comment("Иконка"),
            'image' => $this->text()->null()->comment("Изображение")
        ]);
        // Создание индекса id
        $this->createIndex(
            'idx-menu-id',
            'menu',
            'id'
        );

        // Создание индекса id
        $this->createIndex(
            'idx-menu-item-id',
            'menu_items',
            'id'
        );
        // Создание индекса id
        $this->createIndex(
            'idx-menu-item-uid-id',
            'menu_items',
            'menu_id'
        );
        $this->addForeignKey('menu-relation','menu_items','menu_id','menu','id','CASCADE','CASCADE');
    }

    public function down()
    {

        $this->dropForeignKey('menu-relation','menu_items');
        // Удаление индекса id
        $this->dropIndex(
            'idx-menu-id',
            'menu'
        );
        // Удаление индекса id
        $this->dropIndex(
            'idx-menu-item-id',
            'menu_items'
        );
        // Удаление индекса id
        $this->dropIndex(
            'idx-menu-item-uid-id',
            'menu_items'
        );
        $this->dropTable('menu');
        $this->dropTable('menu_items');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m171123_154416_menu cannot be reverted.\n";

        return false;
    }
    */
}
