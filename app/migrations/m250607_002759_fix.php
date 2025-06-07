<?php

use yii\db\Migration;

class m250607_002759_fix extends Migration
{

    public function up()
    {
        $this->execute("ALTER TABLE `tracks`
            ADD INDEX `status` (`status`);");
    }

    public function down()
    {
        echo "m250607_002759_fix cannot be reverted.\n";

        return false;
    }

}
