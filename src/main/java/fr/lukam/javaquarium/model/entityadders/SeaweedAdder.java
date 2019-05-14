package fr.lukam.javaquarium.model.entityadders;

import com.badlogic.ashley.core.Engine;
import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.AgeComponent;
import fr.lukam.javaquarium.model.components.HealthComponent;
import fr.lukam.javaquarium.model.components.SpeciesComponent;

public class SeaweedAdder implements EntityAdder {

    private final int health;

    public SeaweedAdder(int health) {
        this.health = health;
    }

    @Override
    public void addToEngine(Engine engine) {
        Entity entity = engine.createEntity();
        engine.addEntity(entity);
        entity.add(new SpeciesComponent(SpeciesComponent.SpeciesType.SEAWEED));
        entity.add(new HealthComponent(health, 1));
        entity.add(new AgeComponent());
    }

}
