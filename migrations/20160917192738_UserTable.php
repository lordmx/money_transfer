<?php

use Phpmig\Migration\Migration;

class UserTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `users` ( `id` INT NOT NULL AUTO_INCREMENT , `access_token` VARCHAR(32) NOT NULL , PRIMARY KEY (`id`), INDEX (`access_token`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `users`;';

        $this->getContainer()['db']->query($sql);
    }
}
