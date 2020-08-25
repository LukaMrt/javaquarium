package fr.lukam.javaquarium.domain.model.fish;

import java.util.Arrays;
import java.util.Random;

public enum Sex {

    MALE("male"),
    FEMALE("female");

    private static final Random random = new Random();

    private final String sex;

    Sex(String sex) {
        this.sex = sex;
    }

    public static Sex of(String sexName) {
        return Arrays.stream(values())
                .filter(sex -> sex.sex.equalsIgnoreCase(sexName))
                .findAny()
                .orElseGet(Sex::random);
    }

    public static Sex random() {
        return values()[random.nextInt(2)];
    }

    @Override
    public String
    toString() {
        return sex;
    }

}
