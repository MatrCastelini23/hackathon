import { Repository } from "typeorm";
import { ContratosEntity } from "../models/ContratosEntity";

export interface IContratosRepository {
    getContratoById(id: number): Promise<ContratosEntity | undefined>;
    getContratoAll(): Promise<any[]>;
    getContratosPorEmpresa(empresa_id: number): Promise<any[]>;
    createContrato(data: Omit<ContratosEntity, "id">): Promise<ContratosEntity>;
    saveContrato(entity: ContratosEntity): Promise<ContratosEntity>;
    deleteContrato(id: number): Promise<boolean>;
};

export class ContratosRepository implements IContratosRepository {
    constructor(private readonly repo: Repository<ContratosEntity>) { };

    async getContratoById(id: number): Promise<ContratosEntity | undefined> {
        const linha = await this.repo.findOne({ where: { id } });
        return linha ?? undefined;
    }

    async getContratoAll(): Promise<any[]> {
        const contratos = await this.repo.createQueryBuilder("contrato")
            .leftJoinAndSelect(
                "vagas",
                "vaga",
                "contrato.vagas_id = vaga.id"
            )
            .leftJoinAndSelect(
                "alunos",
                "aluno",
                "contrato.aluno_id = aluno.id"
            )
            .select([
                "contrato.id",
                "contrato.date_inicio",
                "contrato.data_fim",
                "vaga.cargo",
                "vaga.requisitos",
                "aluno.nome",
                "aluno.email",
                "aluno.cpf"
            ])
            .orderBy("contrato.date_inicio", "DESC")
            .getMany();

        return contratos;
    }

    async getContratosPorEmpresa(empresa_id: number): Promise<any[]> {
        const rows = await this.repo.createQueryBuilder("contrato")
            .leftJoin("vagas", "vaga", "contrato.vagas_id = vaga.id")
            .leftJoin("alunos", "aluno", "contrato.aluno_id = aluno.id")
            .leftJoin("empresas", "empresa", "vaga.empresas_id = empresa.id")
            .select("contrato.id", "id")
            .addSelect("DATE_FORMAT(contrato.date_inicio, '%d/%m/%Y')", "date_inicio")
            .addSelect("DATE_FORMAT(contrato.data_fim, '%d/%m/%Y')", "data_fim")
            .addSelect("vaga.cargo", "cargo")
            .addSelect("aluno.nome", "nome")
            .addSelect("aluno.email", "email")
            .addSelect("aluno.cpf", "cpf")
            .where("empresa.id = :empresa_id", { empresa_id })
            .orderBy("contrato.date_inicio", "DESC")
            .getRawMany();
        return rows;
    }

    async createContrato(dados: Omit<ContratosEntity, "id">): Promise<ContratosEntity> {
        const dadosIn = this.repo.create(dados);
        const save = await this.repo.save(dadosIn);
        return (save);
    }

    async saveContrato(entity: ContratosEntity): Promise<ContratosEntity> {
        const save = await this.repo.save(entity);
        return (save);
    }

    async deleteContrato(id: number): Promise<boolean> {
        const rm = await this.repo.delete(id);
        return (rm.affected ?? 0) > 0;
    }

}; 
