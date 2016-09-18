<?php

use Phpmig\Migration\Migration;

class DocumentTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `documents` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `created_at` DATETIME NULL , `executed_at` DATETIME NULL , `status` VARCHAR(32) NOT NULL , `type` VARCHAR(32) NOT NULL , `creator_id` INT(11) NOT NULL , `executor_id` INT NULL , `context` TEXT NOT NULL , `notice` TEXT NOT NULL, `error` TEXT NOT NULL, PRIMARY KEY (`id`), INDEX (`created_at`), INDEX (`status`), INDEX (`type`), INDEX (`creator_id`), INDEX (`executor_id`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `documents`;';

        $this->getContainer()['db']->query($sql);
    }
}
