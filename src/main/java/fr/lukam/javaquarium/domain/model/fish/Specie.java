package fr.lukam.javaquarium.domain.model.fish;

import java.util.Arrays;
import java.util.Random;

public enum Specie {

    MEROU("merou"),
    THON("thon"),
    POISSON_CLOWN("poisson-clown"),
    SOLE("sole"),
    BAR("bar"),
    CARPE("carpe");

    private static final Random random = new Random();

    private final String specie;

    Specie(String specie) {
        this.specie = specie;
    }

    public static Specie of(String specieName) {
        return Arrays.stream(values())
                .filter(specie -> specie.specie.equalsIgnoreCase(specieName))
                .findAny()
                .orElseGet(Specie::random);
    }

    public static Specie random() {
        return values()[random.nextInt(2)];
    }

    @Override
    public String toString() {
        return specie;
    }

}
