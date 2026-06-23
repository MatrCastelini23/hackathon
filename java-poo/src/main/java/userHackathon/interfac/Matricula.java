package userHackathon.interfac;

import userHackathon.dao.AlunoDao;
import userHackathon.dao.EnderecoAlunoDao;
import userHackathon.model.Aluno;
import userHackathon.model.EnderecoAluno;

import java.sql.SQLException;


public interface Matricula {
    static void matricular(Aluno aluno, EnderecoAluno endereco) throws SQLException {
        var alunoDao = new AlunoDao();
        var enderecoDao = new EnderecoAlunoDao();

        if(endereco != null) {
            long enderecoId = enderecoDao.inserir(endereco);

            endereco.setId(enderecoId);
            aluno.setIdEnderecoAluno(enderecoId);
        }
        alunoDao.inserir(aluno);
    }
}
