<?php

use Phpmig\Migration\Migration;

class Oauth2UserScopeTable extends Migration
{
    /**
     * Do the migration
     */
    public function up()
    {
        $sql = 'CREATE TABLE `oauth2_user_scopes` ( `id` INT(11) NOT NULL AUTO_INCREMENT , `user_id` INT(11) NOT NULL , `scope_id` INT(11) NOT NULL , PRIMARY KEY (`id`), INDEX (`user_id`)) ENGINE = InnoDB;';

        $this->getContainer()['db']->query($sql);
    }

    /**
     * Undo the migration
     */
    public function down()
    {
        $sql = 'DROP TABLE `oauth2_user_scopes`;';

        $this->getContainer()['db']->query($sql);
    }
}
