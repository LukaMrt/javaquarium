package fr.lukam.test.model.eaters;

import com.badlogic.ashley.core.Entity;
import fr.lukam.test.model.components.SpeciesComponent;

public class Herbivorous implements Eater {

    @Override
    public boolean canEat(Entity entity) {
        return entity.getComponent(SpeciesComponent.class).speciesType == SpeciesComponent.SpeciesType.SEAWEED;
    }

    @Override
    public int getHealthWon() {
        return 3;
    }

    @Override
    public int getHealthInflicted() {
        return 2;
    }

}
