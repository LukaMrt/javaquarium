package fr.lukam.javaquarium.model.eaters;

import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.SpeciesComponent;
import fr.lukam.javaquarium.model.components.SpeciesType;

public class Herbivorous implements Eater {

    private static final int HEALTH_WON = 3;
    private static final int HEALTH_INFLICTED = 2;

    @Override
    public boolean canEat(Entity entity) {
        return entity.getComponent(SpeciesComponent.class).is(SpeciesType.SEAWEED);
    }

    @Override
    public int getHealthWon() {
        return HEALTH_WON;
    }

    @Override
    public int getHealthInflicted() {
        return HEALTH_INFLICTED;
    }

}
