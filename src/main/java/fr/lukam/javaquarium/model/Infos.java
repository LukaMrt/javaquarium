package fr.lukam.javaquarium.model;

import java.io.File;
import java.io.FileWriter;
import java.io.IOException;
import java.util.*;

public class Infos {

    private final Aquarium aquarium;

    public Infos() {
        aquarium = Aquarium.getInstance();
    }

    public void write() throws IOException {

        File file = new File("C:\\Users\\Luka\\Desktop\\Javaquarium.txt");
        if (!file.exists()) {
            file.createNewFile();
        }

        FileWriter fileWriter = new FileWriter(file);

        fileWriter.write("--------------Tour" + this.aquarium.getTour() + "---------------------");
        fileWriter.write("\n\n");

        seaweedsInfos(fileWriter);
        fishesInfos(fileWriter);

        fileWriter.close();

    }

    private void seaweedsInfos(FileWriter fileWriter) throws IOException {

        fileWriter.write("// 1- Algues\n");

        Map<Integer, Integer> map = new HashMap<>();

        List<Seaweed> seaweeds = new ArrayList<>();

        for (Entity entity : aquarium.getEntities()) {

            if (entity instanceof Seaweed) {

                seaweeds.add((Seaweed) entity);

            }

        }

        for (Seaweed seaweed : seaweeds) {

            if (!map.containsKey(seaweed.getAge())) {
                map.put(seaweed.getAge(), 0);
            }
            map.replace(seaweed.getAge(), map.get(seaweed.getAge()) + 1);

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

        for (Fish fish : this.aquarium.getishes()) {

            fileWriter.write(fish.getName()
                    + ", "
                    + fish.getSex().getFrench()
                    + ", "
                    + fish.getClass().getName().substring(fish.getClass().getName().lastIndexOf(".") + 1)
                    + ", "
                    + fish.getLifePoint()
                    + " points de vie"
                    + ", "
                    + fish.getAge()
                    + " ans\n");

        }

        fileWriter.write("\n\n");

    }

}
