import { hash } from "bcrypt";
import { IAlunoRepository } from "../repositories/AlunoRepository";
import {  AlunoPublico } from "../models/AlunosEntity";
import AppError from "../utils/AppErrors";

export class AlunoService{
    constructor(private readonly aluno: IAlunoRepository){}

    async listarAlunoPorId(id: number): Promise<AlunoPublico>{
        const aluno = await this.aluno.getAlunoById(id);
        if(!aluno){
            throw new AppError(404, "Usuario não encontrado");
        }
        const { senha: _s, ...alunoPublico} = aluno;
        return alunoPublico;
    }
}