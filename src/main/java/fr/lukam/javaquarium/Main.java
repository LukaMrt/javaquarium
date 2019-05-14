package fr.lukam.javaquarium;

import com.badlogic.ashley.core.Engine;
import fr.lukam.javaquarium.model.CommandExecutor;
import fr.lukam.javaquarium.model.entityadders.SeaweedAdder;
import fr.lukam.javaquarium.model.systems.EatSystem;
import fr.lukam.javaquarium.model.systems.ReproduceSystem;
import fr.lukam.javaquarium.model.systems.UpdateSystem;

import java.io.File;
import java.io.IOException;

public class Main {

    public static void main(String[] args) throws IOException {

        Engine engine = new Engine();

        engine.addSystem(new UpdateSystem());
        engine.addSystem(new EatSystem());
        engine.addSystem(new ReproduceSystem());

        for (int i = 0; i < 10; i++) {
            new SeaweedAdder(10).addToEngine(engine);
        }

        createFile();
        new CommandExecutor(engine).readCommands();

    }

    private static void createFile() {

        File file = new File("C:\\Users\\Luka\\Desktop\\Javaquarium.txt");

        if (file.exists()) {
            file.delete();
        }

    }


}
