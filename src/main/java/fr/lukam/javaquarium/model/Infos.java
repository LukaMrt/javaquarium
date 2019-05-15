package fr.lukam.javaquarium.model;

import com.badlogic.ashley.core.Engine;
import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.*;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.*;

public class Infos {

    private final Engine engine;

    public Infos(Engine engine) {
        this.engine = engine;
    }

    public void write() throws IOException {

        File file = new File("C:\\Users\\Luka\\Desktop\\Javaquarium.txt");
        if (!file.exists()) {
            file.createNewFile();
        }

        FileWriter fileWriter = new FileWriter(file);

        fileWriter.write("--------------Tour---------------------");
        fileWriter.write("\n\n");

        seaweedsInfos(fileWriter);
        fishesInfos(fileWriter);

        fileWriter.close();

    }

    private void seaweedsInfos(FileWriter fileWriter) throws IOException {

        fileWriter.write("// 1- Algues\n");

        Map<Integer, Integer> map = new HashMap<>();

        List<Entity> seaweeds = new ArrayList<>();

        for (Entity entity : engine.getEntities()) {
            if (entity.getComponent(SpeciesComponent.class).speciesType == SpeciesType.SEAWEED) {
                seaweeds.add(entity);
            }
        }

        for (Entity seaweed : seaweeds) {

            int age = seaweed.getComponent(AgeComponent.class).age;

            if (!map.containsKey(age)) {
                map.put(age, 0);
            }
            map.replace(age, map.get(age) + 1);

        }

        Map<Integer, Integer> mapSorted = new TreeMap<>(map);

        for (Map.Entry<Integer, Integer> entry : mapSorted.entrySet()) {

            fileWriter.write(entry.getValue()
                    + " algues "
                    + entry.getKey()
                    + " ans\n");

        }

        fileWriter.write("\n\n");

    }

    private void fishesInfos(FileWriter fileWriter) throws IOException {

        fileWriter.write("// 2- Poissons\n");

        List<Entity> fishes = new ArrayList<>();

        for (Entity entity : engine.getEntities()) {
            if (entity.getComponent(SpeciesComponent.class).speciesType != SpeciesType.SEAWEED) {
                fishes.add(entity);
            }
        }

        for (Entity fish : fishes) {

            fileWriter.write(fish.getComponent(NameComponent.class).name
                    + ", "
                    + fish.getComponent(SexComponent.class).sex
                    + ", "
                    + fish.getComponent(SpeciesComponent.class).speciesType.speciesClass
                    + ", "
                    + fish.getComponent(HealthComponent.class).health
                    + " points de vie"
                    + ", "
                    + fish.getComponent(AgeComponent.class).age
                    + " ans\n");

        }

        fileWriter.write("\n\n");

    }

}
