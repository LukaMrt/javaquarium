package fr.lukam.javaquarium.model.reproducers;

import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.SexComponent;

public class Hermaphrodite implements Reproducer {

    @Override
    public boolean mustChange(Entity entity, Entity otherEntity) {
        boolean areNotSameSex = entity
                .getComponent(SexComponent.class)
                .sex == otherEntity.getComponent(SexComponent.class).sex;
        return areNotSameSex;
    }

}
