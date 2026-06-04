package userHackathon;

import userHackathon.gui.UserGui;

import javax.swing.*;
import java.security.Principal;

public class Main {
    static void main(String[] args) throws UnsupportedLookAndFeelException {
        UIManager.setLookAndFeel(UIManager.getLookAndFeel());
        SwingUtilities.invokeLater(Main::executar);
    }

    private static void executar(){
        var gui = new UserGui();
        gui.setVisible(true);
    }
}
