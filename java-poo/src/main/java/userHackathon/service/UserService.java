package userHackathon.service;

import userHackathon.dao.UserDao;
import userHackathon.model.User;

import java.util.ArrayList;
import java.util.List;

public class UserService {

    public void salvar(User user){
        try{
            var dao = new UserDao();
            if(user.getId() == null) {
                dao.cadastrarUsuario(user);
            }
            else {
                dao.atualizarUsuario(user);
            }
        } catch (Exception e) {
            System.out.println("Erro no service " + e.getMessage());;
        }
    }

    public List<User> listar(){
        try{
            var dao = new UserDao();
            return dao.listar();
        } catch (Exception e) {
            System.out.println("Erro no service " + e.getMessage());
            return new ArrayList<>();
        }
    }

    public void deletar(User user){
        try {
            var dao = new UserDao();
            if(user.getId() == null){
                //mudar mensagem pra apresentar
                System.out.println("Cade o ID?");
            }else{
                dao.deletarUsuario(user);
            }
        }catch (Exception e){
            System.out.println("Erro no deletar User dao " + e.getMessage());
        }
    }
}
