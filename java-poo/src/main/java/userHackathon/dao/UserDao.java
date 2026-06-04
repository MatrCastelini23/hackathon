package userHackathon.dao;

import userHackathon.model.User;

import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class UserDao extends Dao{

    public List<User> listar() throws SQLException{
        List<User> users = new ArrayList<>();
        try{
            var resultSet = getConnection()
                    .prepareStatement("select * from users")
                    .executeQuery();
            while(resultSet.next()){
                var u = new User();
                u.setId(resultSet.getLong("id"));
                u.setName(resultSet.getString("name"));
                u.setEmail(resultSet.getString("email"));
                u.setPassword(resultSet.getString("password"));
                users.add(u);
            }
        } catch (SQLException e) {
            System.out.println("Erro no UserDao " + e.getMessage());
        }
        return users;
    }

    public void cadastrarUsuario(User user) throws SQLException{
        var sqlInsert = "insert into users (name, email, senha) values (?,?,?)";
        var ps = getConnection().prepareStatement(sqlInsert);
        ps.setString(1, user.getName());
        ps.setString(2, user.getEmail());
        ps.setString(3, user.getPassword());
        ps.execute();
    }

    public void atualizarUsuario(User user)throws SQLException{
        var sqlUpdate = "udpate user set nome = ?, email=?, password=? where id=?";
        var ps = getConnection().prepareStatement(sqlUpdate);
        ps.setString(1, user.getName());
        ps.setString(2, user.getEmail());
        ps.setString(3, user.getPassword());
        ps.execute();
    }

    public void deletarUsuario(User user) throws SQLException{
        var sqlDelete = "delete from users where id=?";
        var ps = getConnection().prepareStatement(sqlDelete);
        ps.setLong(1, user.getId());
        ps.execute();
    }
}
