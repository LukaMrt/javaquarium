package fr.lukam.test.model;

import com.badlogic.ashley.core.Engine;
import fr.lukam.test.model.components.SexComponent;
import fr.lukam.test.model.components.SpeciesComponent;
import fr.lukam.test.model.entityadders.FishAdder;

import java.io.IOException;
import java.util.Scanner;

public class CommandExecutor {

    private Engine engine;
    private Scanner scanner;

    public CommandExecutor(Engine engine) {
        this.engine = engine;
        this.scanner = new Scanner(System.in);
    }

    public void readCommands() throws IOException {

        boolean run = true;

        while (run) {

            String line = scanner.nextLine();

            if (line.equalsIgnoreCase("tour")) {
                engine.update(1);
                new Infos(engine).write();
            } else {

                String[] tab = line.split(" ");

                if (tab[0].equalsIgnoreCase("add") && SpeciesComponent.SpeciesType.isFish(tab[1])) {

                    try {
                        new FishAdder(tab[2], SexComponent.SexType.getRandom(), SpeciesComponent.SpeciesType.getFromString(tab[1]).speciesClass.newInstance()).addToEngine(engine);
                    } catch (InstantiationException | IllegalAccessException e) {
                        e.printStackTrace();
                    }

                } else if (tab[0].equalsIgnoreCase("stop")) {

                    run = false;

                } else {

                    try {
                        new FishAdder("name", SexComponent.SexType.getRandom(), SpeciesComponent.SpeciesType.getFromString(tab[1]).speciesClass.newInstance()).addToEngine(engine);
                    } catch (InstantiationException | IllegalAccessException e) {
                        e.printStackTrace();
                    }

                }

            }

        }

    }

}
