<?php

use yii\db\Migration;

class m250604_015816_init extends Migration
{
    public function up()
    {
        $this->execute("CREATE TABLE `tracks` (
                `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
                `track_number` VARCHAR(50) NOT NULL COMMENT 'Уникальный идентификатор',
                `created_at` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP()) COMMENT 'Дата и время создания',
                `updated_at` DATETIME NOT NULL DEFAULT (CURRENT_TIMESTAMP()) ON UPDATE CURRENT_TIMESTAMP() COMMENT 'Дата и время обновления',
                `status` VARCHAR(50) NOT NULL COMMENT 'Статус',
                PRIMARY KEY (`id`),
                UNIQUE INDEX `track_number` (`track_number`)
            )
            COLLATE='utf8_general_ci';");
        $this->execute("CREATE TABLE `users` (
                `id` INT NOT NULL AUTO_INCREMENT COMMENT 'Первичный ключ',
                `token` VARCHAR(50) NOT NULL COMMENT 'Токен',
                `name` VARCHAR(50) NOT NULL COMMENT 'Имя',
                PRIMARY KEY (`id`)
            )
            COLLATE='utf8_general_ci';");
        $token = Yii::$app->security->generateRandomString(10);
        $this->execute("INSERT INTO `users` (`token`, `name`) VALUES ('{$token}', 'Test')");
    }

    public function down()
    {
        echo "m250604_015816_init cannot be reverted.\n";

        return false;
    }

}
