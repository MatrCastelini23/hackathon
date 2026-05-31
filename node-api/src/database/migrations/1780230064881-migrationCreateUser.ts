import { MigrationInterface, QueryRunner } from "typeorm";

export class MigrationCreateUser1780230064881 implements MigrationInterface {
    name = 'MigrationCreateUser1780230064881'
    public async up(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`CREATE TABLE "user" ("id" integer PRIMARY KEY AUTOINCREMENT NOT NULL, "name" VARCHAR(100) NOT NULL, "email" VARCHAR(100) NOT NULL, "password" VARCHAR(100) NOT NULL)`);
    }

    public async down(queryRunner: QueryRunner): Promise<void> {
        await queryRunner.query(`DROP TABLE "user"`);
    }

}
