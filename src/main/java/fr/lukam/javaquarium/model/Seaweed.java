package fr.lukam.javaquarium.model;

import java.util.List;

public class Seaweed extends Entity {

    @Override
    public Entity eat(List<Entity> entities) {
        return null;
    }

    @Override
    public void age() {
        addAge();
        addLifePoint(1);
    }

    @Override
    public Entity reproduce() {
        return null;
    }

}
