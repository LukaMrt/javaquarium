package fr.lukam.javaquarium.model.entityadders;

import com.badlogic.ashley.core.Engine;
import com.badlogic.ashley.core.Entity;
import fr.lukam.javaquarium.model.components.*;
import fr.lukam.javaquarium.model.fishes.Fish;

public class FishAdder {

    private static final int FISH_HEAlTH = 10;

    private final String name;
    private final SexComponent.SexType sex;
    private final Fish fish;

    public FishAdder(String name, SexComponent.SexType sex, Fish fish) {
        this.name = name;
        this.sex = sex;
        this.fish = fish;
    }

    public void addToEngine(Engine engine) {
        Entity entity = engine.createEntity();
        engine.addEntity(entity);
        entity.add(new NameComponent(name));
        entity.add(new SexComponent(sex));
        entity.add(new EaterComponent(fish.getEater()));
        entity.add(new SpeciesComponent(fish.getSpeciesType()));
        entity.add(new ReproducerComponent(fish.getReproducer()));
        entity.add(new HealthComponent(FISH_HEAlTH, -1));
        entity.add(new AgeComponent());
    }

}
