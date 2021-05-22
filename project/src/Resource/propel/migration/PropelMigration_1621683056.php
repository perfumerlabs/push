<?php

use Propel\Generator\Manager\MigrationManager;

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1621683056.
 * Generated on 2021-05-22 11:30:56 by root
 */
class PropelMigration_1621683056
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

CREATE TABLE "push_log"
(
    "id" serial NOT NULL,
    "users" JSON,
    "push" JSON,
    "created_at" TIMESTAMP,
    PRIMARY KEY ("id")
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

DROP TABLE IF EXISTS "push_log" CASCADE;

COMMIT;
',
);
    }

}