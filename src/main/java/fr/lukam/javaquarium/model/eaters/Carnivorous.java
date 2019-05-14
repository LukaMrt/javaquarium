package fr.lukam.javaquarium.model.eaters;

import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.SpeciesComponent;

public class Carnivorous implements Eater {

    @Override
    public boolean canEat(Entity entity) {
        return entity.getComponent(SpeciesComponent.class).speciesType != SpeciesComponent.SpeciesType.SEAWEED;
    }

    @Override
    public int getHealthWon() {
        return 5;
    }

    @Override
    public int getHealthInflicted() {
        return 4;
    }

}
