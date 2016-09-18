<?php

use Phpmig\Migration\Migration;

class Oauth2ScopeTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `oauth2_scopes` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `alias` VARCHAR(32) NOT NULL , PRIMARY KEY (`id`), INDEX (`alias`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `oauth2_scopes`;';

        $this->getContainer()['db']->query($sql);
    }
}
