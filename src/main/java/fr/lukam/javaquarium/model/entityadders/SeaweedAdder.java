package fr.lukam.javaquarium.model.entityadders;

import com.badlogic.ashley.core.Engine;
import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.AgeComponent;
import fr.lukam.javaquarium.model.components.HealthComponent;
import fr.lukam.javaquarium.model.components.SpeciesComponent;
import fr.lukam.javaquarium.model.components.SpeciesType;

public class SeaweedAdder {

    private final int health;

    public SeaweedAdder(int health) {
        this.health = health;
    }

    public void addToEngine(Engine engine) {
        Entity entity = engine.createEntity();
        entity.add(new SpeciesComponent(SpeciesType.SEAWEED));
        entity.add(new HealthComponent(health, 1));
        entity.add(new AgeComponent());
        engine.addEntity(entity);
    }

}
