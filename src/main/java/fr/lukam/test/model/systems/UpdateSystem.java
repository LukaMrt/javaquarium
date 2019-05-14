package fr.lukam.test.model.systems;

import com.badlogic.ashley.core.Entity;
import com.badlogic.ashley.core.Family;
import com.badlogic.ashley.systems.IteratingSystem;
import fr.lukam.test.model.components.*;
import fr.lukam.test.model.entityadders.SeaweedAdder;

public class UpdateSystem extends IteratingSystem {

    public UpdateSystem() {
        super(Family.all(AgeComponent.class, HealthComponent.class).get());
    }

    @Override
    protected void processEntity(Entity entity, float deltaTime) {

        int age = entity.getComponent(AgeComponent.class).age;
        entity.getComponent(AgeComponent.class).age = ++age;
        if (++age >= 20) getEngine().removeEntity(entity);

        entity.remove(EatenComponent.class);

        entity.getComponent(HealthComponent.class).update();

        if (entity.getComponent(SpeciesComponent.class).speciesType == SpeciesComponent.SpeciesType.SEAWEED) {

            if (age >= 10) {

                int newAge = age % 2 == 0 ? age / 2 : (int) (age / 2 - 0.5);
                new SeaweedAdder(newAge).addToEngine(getEngine());
                entity.getComponent(AgeComponent.class).age = newAge;

            }

        }

    }

}
