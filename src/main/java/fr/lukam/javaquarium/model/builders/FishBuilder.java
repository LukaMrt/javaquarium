package fr.lukam.javaquarium.model.builders;

import fr.lukam.javaquarium.enums.Sex;
import fr.lukam.javaquarium.interfaces.Eater;
import fr.lukam.javaquarium.interfaces.Reproduction;
import fr.lukam.javaquarium.model.Fish;

public final class FishBuilder<T extends Fish> {
    public Eater eater;
    public Reproduction reproduction;
    public String name;
    public Sex sex;

    private FishBuilder() {
    }

    public static FishBuilder aFish() {
        return new FishBuilder();
    }

    public FishBuilder withEater(Eater eater) {
        this.eater = eater;
        return this;
    }

    public FishBuilder withSexChanger(Reproduction reproduction) {
        this.reproduction = reproduction;
        return this;
    }

    public FishBuilder withName(String name) {
        this.name = name;
        return this;
    }

    public FishBuilder withSex(Sex sex) {
        this.sex = sex;
        return this;
    }

    public FishBuilder build() {
        return this;
    }

}
