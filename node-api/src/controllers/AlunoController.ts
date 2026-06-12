import type { Request, Response, NextFunction } from 'express';
import type { AlunoService } from '../services/AlunoService';
import AppError from '../utils/AppErrors';

export class AlunoController{
    constructor(private readonly alunoSerivice: AlunoService){};

    listAlunoPorId = async(req: Request, res: Response, next: NextFunction) =>{
        try {
            const id = Number(req.params.id);
            if(!Number.isInteger(id) || id < 1){
                throw new AppError(400, "Parametro errado");
            }
            const alunos = await this.alunoSerivice.listarAlunoPorId(id);
            if(!alunos){
                throw new AppError(404, "Não encontrado")
            }
            res.json({ alunos });
        } catch (error) {
            next(error)
        }
    }
}