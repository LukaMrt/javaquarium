package fr.lukam.javaquarium.model.reproducers;


import com.badlogic.ashley.core.Entity;

public class OneSex implements Reproducer {

    @Override
    public boolean mustChange(Entity entity, Entity otherEntity) {
        return false;
    }

}
