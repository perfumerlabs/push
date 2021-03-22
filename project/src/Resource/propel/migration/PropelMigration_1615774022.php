<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1615774022.
 * Generated on 2021-03-15 02:07:02 by root
 */
class PropelMigration_1615774022
{
    public $comment = '';

    public function preUp(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postUp(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    public function preDown(MigrationManager $manager)
    {
        // add the pre-migration code here
    }

    public function postDown(MigrationManager $manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'push' => '
BEGIN;

CREATE TABLE "push_token"
(
    "customer_token" VARCHAR(255) NOT NULL,
    "apple_token" VARCHAR(255),
    "google_token" VARCHAR(255),
    "huawei_token" VARCHAR(255),
    "web_token" VARCHAR(255),
    PRIMARY KEY ("customer_token")
);

COMMIT;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'push' => '
BEGIN;

DROP TABLE IF EXISTS "push_token" CASCADE;

COMMIT;
',
);
    }

}