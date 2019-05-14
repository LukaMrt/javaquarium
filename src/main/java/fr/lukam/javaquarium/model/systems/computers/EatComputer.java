package fr.lukam.javaquarium.model.systems.computers;

import com.badlogic.ashley.core.Engine;
import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.EaterComponent;
import fr.lukam.javaquarium.model.components.HealthComponent;
import fr.lukam.javaquarium.model.components.SpeciesComponent;
import fr.lukam.javaquarium.model.eaters.Eater;
import fr.lukam.javaquarium.model.components.EatenComponent;

import java.util.ArrayList;
import java.util.List;

public class EatComputer {

    private final Entity entity;

    public EatComputer(Entity entity) {
        this.entity = entity;
    }

    public void compute(Engine engine) {

        Entity eatenEntity = getEatenEntity(engine);

        if (canEat(eatenEntity)) {
            eat(eatenEntity);
        }

        if (eatenEntity.getComponent(HealthComponent.class).health <= 0) {
            engine.removeEntity(eatenEntity);
        }

    }

    private Entity getEatenEntity(Engine engine) {
        Eater eater = entity.getComponent(EaterComponent.class).eater;
        List<Entity> matchEntities = new ArrayList<>();

        for (Entity entity1 : engine.getEntities()) {
            if (eater.canEat(entity1)) {
                matchEntities.add(entity1);
            }
        }

        matchEntities.remove(entity);

        return matchEntities.stream().findAny().orElse(entity);
    }

    private boolean canEat(Entity eatenEntity) {
        HealthComponent healthComponent = entity.getComponent(HealthComponent.class);
        SpeciesComponent.SpeciesType entitySpecies = entity.getComponent(SpeciesComponent.class).speciesType;
        SpeciesComponent.SpeciesType eatenEntitySpecies = eatenEntity.getComponent(SpeciesComponent.class).speciesType;

        boolean areNotSameInstance = entity != eatenEntity;
        boolean isHealthOk = healthComponent.health <= 5;
        boolean areNotSameSpecies = entitySpecies != eatenEntitySpecies;

        return areNotSameInstance && isHealthOk && areNotSameSpecies;
    }

    private void eat(Entity eatenEntity) {
        Eater eater = entity.getComponent(EaterComponent.class).eater;
        HealthComponent entityHealth = entity.getComponent(HealthComponent.class);
        HealthComponent eatenHealth = eatenEntity.getComponent(HealthComponent.class);

        entityHealth.health = entityHealth.health + eater.getHealthWon();
        eatenHealth.health = eatenHealth.health - eater.getHealthInflicted();
        eatenEntity.add(new EatenComponent());
    }

}
