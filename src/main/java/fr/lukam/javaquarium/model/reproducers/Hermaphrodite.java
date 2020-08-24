package fr.lukam.javaquarium.model.reproducers;

import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.SexComponent;

public class Hermaphrodite implements Reproducer {

    @Override
    public boolean mustChange(Entity entity, Entity otherEntity) {
        return entity.getComponent(SexComponent.class).isSame(otherEntity.getComponent(SexComponent.class));
    }

}
