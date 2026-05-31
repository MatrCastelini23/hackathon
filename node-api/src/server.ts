import "reflect-metadata";
import "dotenv";
import express, {NextFunction, Request, Response} from 'express';
import cors from 'cors';
import userRouter from './routes/userRouter.js';
import { AppDataSource } from "./database/data-source.js";

const server = express();
const PORT = process.env.PORT;
server.use(cors())

// permite que o Node entenda JSON nativamente
server.use(express.json());

server.use('/users', userRouter);


server.listen(Number(PORT), () => {
    console.log(`Server running on port ` + PORT);
});

AppDataSource.initialize()
    .then(() => {
        console.log('Database connection has been initialized!');
    })
    .catch((err) => {
        console.error('Error during Data Source initialization:', err);
    });