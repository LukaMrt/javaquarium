package fr.lukam.javaquarium.model;

import fr.lukam.javaquarium.enums.Sex;
import fr.lukam.javaquarium.interfaces.Eater;
import fr.lukam.javaquarium.interfaces.Reproduction;
import fr.lukam.javaquarium.model.builders.FishBuilder;
import fr.lukam.javaquarium.model.eaters.Carnivore;
import fr.lukam.javaquarium.utils.Utils;

import java.util.List;

public abstract class Fish extends Entity {

    private String name;
    private Sex sex;

    private Eater eater;
    private Reproduction reproduction;

    protected Fish(FishBuilder fishBuilder) {
        this.name = fishBuilder.name;
        this.sex = fishBuilder.sex;
        this.eater = fishBuilder.eater;
        this.reproduction = fishBuilder.reproduction;
    }

    public String getName() {
        return name;
    }

    public Sex getSex() {
        return sex;
    }

    @Override
    public Entity eat(List<Entity> entities) {

        Entity eatenEntity = eater.eat(entities);

        if (eatenEntity == null) {
            return null;
        }

        while (eatenEntity.equals(this)) {
            eatenEntity = eater.eat(entities);
        }

        if (!eatenEntity.getClass().equals(this.getClass())) {

            if (eater instanceof Carnivore) {

                addLifePoint(5);
                eatenEntity.addLifePoint(-4);

            } else {

                addLifePoint(3);
                eatenEntity.addLifePoint(-2);

            }

        }

        return eatenEntity;

    }

    @Override
    public Entity reproduce() {

        Fish otherFish = Utils.getRandomFish();

        if (otherFish == null) {
            return null;
        }

        while (otherFish.equals(this)) {
            otherFish = Utils.getRandomFish();
        }

        this.sex = reproduction.reproduce(this, otherFish) ? Utils.getOtherSex(this.sex) : this.sex;

        return otherFish;

    }

    @Override
    public void age() {
        addAge();
        addLifePoint(-1);
    }

}
