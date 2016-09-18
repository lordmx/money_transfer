<?php

use Phpmig\Migration\Migration;

class Oauth2SessionTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `oauth2_sessions` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL , `hash` VARCHAR(64) NOT NULL , `created_at` DATETIME NOT NULL , PRIMARY KEY (`id`), INDEX (`user_id`), INDEX (`hash`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `oauth2_sessions`;';

        $this->getContainer()['db']->query($sql);
    }
}
