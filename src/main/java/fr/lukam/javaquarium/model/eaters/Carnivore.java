package fr.lukam.javaquarium.model.eaters;

import fr.lukam.javaquarium.interfaces.Eater;
import fr.lukam.javaquarium.model.Entity;
import fr.lukam.javaquarium.model.Fish;
import fr.lukam.javaquarium.utils.Utils;

import java.util.List;

public class Carnivore implements Eater {

    @Override
    public Fish eat(List<Entity> entities) {
        return Utils.getRandomFish();
    }

}
