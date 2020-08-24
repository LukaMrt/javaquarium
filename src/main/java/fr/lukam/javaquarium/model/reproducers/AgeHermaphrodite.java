package fr.lukam.javaquarium.model.reproducers;

import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.AgeComponent;

public class AgeHermaphrodite implements Reproducer {

    @Override
    public boolean mustChange(Entity entity, Entity otherEntity) {
        return entity.getComponent(AgeComponent.class).isTen();
    }

}
