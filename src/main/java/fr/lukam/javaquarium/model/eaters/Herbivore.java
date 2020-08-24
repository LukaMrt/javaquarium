package fr.lukam.javaquarium.model.eaters;

import fr.lukam.javaquarium.interfaces.Eater;
import fr.lukam.javaquarium.model.Entity;
import fr.lukam.javaquarium.model.Seaweed;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;

public class Herbivore implements Eater {

    @Override
    public Entity eat(List<Entity> entities) {

        List<Seaweed> seaweeds = new ArrayList<>();

        for (Entity entity : entities) {

            if (entity instanceof Seaweed) {

                seaweeds.add((Seaweed) entity);

            }

        }

        return seaweeds.isEmpty() ? null : seaweeds.get(new Random().nextInt(seaweeds.size()));

    }

}
