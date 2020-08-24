package fr.lukam.javaquarium;

import fr.lukam.javaquarium.model.CommandExecutor;

import java.io.File;
import java.io.IOException;

public class Main {

    public static void main(String[] args) throws IOException {

        createFile();
        new CommandExecutor().readCommands();

    }

    private static void createFile() {

        File file = new File("C:\\Users\\Luka\\Desktop\\Javaquarium.txt");

        if (file.exists()) {
            file.delete();
        }

    }

}
