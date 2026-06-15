import type { Request, Response, NextFunction } from 'express';
import type { VagasService} from '../services/VagasService';
import AppError from '../utils/AppErrors';
import z from "zod";


export class VagasController{
    constructor(private readonly vagasService: VagasService){};

    private schemaVaga = z.object({
        id: z.number().optional(),
        cargo: z.string({message: "Cargo é obrigatório"}).max(100),
        vaga_preenchida: z.boolean({message: "Vaga preenchida é obrigatória"}),
        data_abertura: z.date({message: "Data de abertura é obrigatória"}),
        data_fechamento: z.date({message: "Data de fechamento é obrigatória"}),
        empresas_id: z.number({message: "ID da empresa é obrigatório"}),
    })

    listVagas = async(req: Request, res: Response, next: NextFunction) =>{
        try {
            const dados = this.schemaVaga.parse(req.body);
            const vagas = await this.vagasService.listarVagasPorId(dados.id!);
            if(!vagas){
                throw new AppError(404, "Vaga não encontrada")
            }
            res.status(200).json({message: "Lista de Vagas", vagas});
        } catch (error) {
            next(error)
        }
    }

}