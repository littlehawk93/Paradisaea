<?php

use yii\db\Migration;

/**
 * Handles the creation of table `device`.
 */
class m231021_205644_create_device_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $queryText = "CREATE TABLE `device` (\n";
        $queryText .= "	`id` CHAR(36) NOT NULL,\n";
        $queryText .= "	`view_id` CHAR(36) UNIQUE NOT NULL,\n";
        $queryText .= " `name` VARCHAR(100) NOT NULL,\n";
        $queryText .= " `width` INTEGER NOT NULL,\n";
        $queryText .= " `height` INTEGER NOT NULL,\n";
        $queryText .= "	`created_at` CHAR(25) NOT NULL,\n";
        $queryText .= "	`created_by` VARCHAR(50) NOT NULL,\n";
        $queryText .= "	`updated_at` CHAR(25) NOT NULL,\n";
        $queryText .= "	`updated_by` VARCHAR(50) NOT NULL,\n";
        $queryText .= "	`deleted` INTEGER DEFAULT 0 NOT NULL,\n";
        $queryText .= "	PRIMARY KEY (`id`)\n";
        $queryText .= ");";

        $this->execute($queryText);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('device');
    }
}
