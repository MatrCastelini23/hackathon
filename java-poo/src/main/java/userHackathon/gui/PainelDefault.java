package userHackathon.gui;

import javax.swing.*;
import java.awt.*;

public interface PainelDefault {

    default void painelAdd(JPanel painel, Component component, int col, int row){
        var contraints = new GridBagConstraints();
        contraints.insets = new Insets(5,5,5,5);
        contraints.gridx = col;
        contraints.gridy = row;
        painel.add(component,contraints);
    }
}
