package fr.lukam.javaquarium.model.systems;

import com.badlogic.ashley.core.Entity;
import com.badlogic.ashley.core.Family;
import com.badlogic.ashley.systems.IteratingSystem;
import fr.lukam.javaquarium.model.components.ReproducerComponent;
import fr.lukam.javaquarium.model.systems.computers.ReproduceComputer;

public class ReproduceSystem extends IteratingSystem {

    public ReproduceSystem() {
        super(Family.all(ReproducerComponent.class).get());
    }

    @Override
    protected void processEntity(Entity entity, float deltaTime) {

        new ReproduceComputer(entity).compute(getEngine());

    }

}
