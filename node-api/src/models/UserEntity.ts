import { Column, 
    Entity, 
    PrimaryGeneratedColumn, 
} from "typeorm";

@Entity({name : "users"})
export class UserEntity { 
    @PrimaryGeneratedColumn()
    id!: number;

    @Column({ type: "varchar",})
    name!: string;

    @Column({ type: "varchar", unique: true})
    email!: string;

    @Column({ type: "varchar"})
    password!: string;

}

export type PublicUser = Omit<UserEntity, "password">;