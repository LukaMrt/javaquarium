package fr.lukam.test.model.reproducers;

import com.badlogic.ashley.core.Entity;

public interface Reproducer {

    boolean mustChange(Entity entity, Entity otherEntity);

}
