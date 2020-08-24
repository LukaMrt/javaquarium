package fr.lukam.javaquarium.model;

import fr.lukam.javaquarium.utils.Utils;

import java.io.IOException;
import java.util.Scanner;

public class CommandExecutor {

    private Aquarium aquarium;
    private Scanner scanner;

    public CommandExecutor() {
        this.aquarium = Aquarium.getInstance();
        this.scanner = new Scanner(System.in);
    }

    public void readCommands() throws IOException {

        boolean a = true;

        while (a) {

            String str = scanner.nextLine();


            if (str.equalsIgnoreCase("tour")) {
                aquarium.tour();
                new Infos().write();
            } else {

                String[] tab = str.split(" ");

                if (tab[0].equalsIgnoreCase("add") && Utils.isFish(tab[1])) {

                    aquarium.addFish(tab[2], Utils.getFishEnum(tab[1]));

                } else if (tab[0].equalsIgnoreCase("stop")) {

                    a = false;

                } else {

                    aquarium.addFish(tab[1]);

                }

            }

        }

    }

}
