package fr.lukam.test.model.systems.computers;

import com.badlogic.ashley.core.Engine;
import com.badlogic.ashley.core.Entity;
import com.badlogic.ashley.core.Family;
import com.badlogic.ashley.utils.ImmutableArray;
import fr.lukam.test.model.components.*;
import fr.lukam.test.model.entityadders.FishAdder;

import java.util.Random;

public class ReproduceComputer {

    private final Entity entity;

    public ReproduceComputer(Entity entity) {
        this.entity = entity;
    }

    public void compute(Engine engine) {

        Entity otherEntity = getOtherEntity(engine);

        if (canReproduce(otherEntity)) {
            changeSex(entity, otherEntity);

            boolean areNotSameSex = entity.getComponent(SexComponent.class).sex != otherEntity.getComponent(SexComponent.class).sex;
            if (areNotSameSex) {

                try {
                    new FishAdder("test",
                            SexComponent.SexType.getRandom(),
                            entity.getComponent(SpeciesComponent.class).speciesType.speciesClass.newInstance())
                            .addToEngine(engine);
                } catch (InstantiationException | IllegalAccessException e) {
                    e.printStackTrace();
                }

            }

        }

    }

    private Entity getOtherEntity(Engine engine) {
        ImmutableArray<Entity> family = engine.getEntitiesFor(Family.all(EaterComponent.class).get());

        Entity otherEntity;
        do {
            otherEntity = family.get(new Random().nextInt(family.size()));
        } while (otherEntity == entity);

        return otherEntity;
    }

    private boolean canReproduce(Entity otherEntity) {
        SpeciesComponent.SpeciesType entitySpecies = entity.getComponent(SpeciesComponent.class).speciesType;
        SpeciesComponent.SpeciesType otherEntitySpecies = otherEntity.getComponent(SpeciesComponent.class).speciesType;

        boolean isHealthOk = entity.getComponent(HealthComponent.class).health > 5;
        boolean areSameSpecies = entitySpecies == otherEntitySpecies;

        return isHealthOk && areSameSpecies;
    }

    private void changeSex(Entity entity, Entity otherEntity) {
        boolean mustChangeSex = entity.getComponent(ReproducerComponent.class).reproducer.mustChange(entity, otherEntity);
        if (mustChangeSex) {
            entity.getComponent(SexComponent.class).sex = entity.getComponent(SexComponent.class).sex.getOtherSex();
        }
    }

}
