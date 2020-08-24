package fr.lukam.javaquarium.model.systems;

import com.badlogic.ashley.core.Entity;
import com.badlogic.ashley.core.Family;
import com.badlogic.ashley.systems.IteratingSystem;
import fr.lukam.javaquarium.model.components.*;
import fr.lukam.javaquarium.model.entityadders.SeaweedAdder;

public class UpdateSystem extends IteratingSystem {

    public UpdateSystem() {
        super(Family.all(AgeComponent.class, HealthComponent.class).get());
    }

    @Override
    protected void processEntity(Entity entity, float deltaTime) {

        AgeComponent age = entity.getComponent(AgeComponent.class);

        if (age.isOver(20)) {
            getEngine().removeEntity(entity);
            return;
        }

        entity.remove(EatenComponent.class);

        entity.getComponent(HealthComponent.class).update();

        if (entity.getComponent(SpeciesComponent.class).is(SpeciesType.SEAWEED) && age.isOver(10)) {
            new SeaweedAdder(age.updateAge()).addToEngine(getEngine());
        }

    }

}
