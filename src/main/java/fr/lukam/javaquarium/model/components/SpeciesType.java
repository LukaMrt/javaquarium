package fr.lukam.javaquarium.model.components;

import fr.lukam.javaquarium.model.fishes.*;

import java.util.Arrays;

public enum SpeciesType {

    BAR(Bar.class, "bar"),

    CARPE(Carpe.class, "carpe"),

    MEROU(Merou.class, "merou"),

    POISSONCLOWN(PoissonClown.class, "poissonclown"),

    SOLE(Sole.class, "sole"),

    THON(Thon.class, "thon"),

    SEAWEED(null, "algue");

    public final Class<? extends Fish> speciesClass;
    private final String name;

    SpeciesType(Class<? extends Fish> speciesClass, String name) {
        this.speciesClass = speciesClass;
        this.name = name;
    }

    public static boolean isFish(String specie) {
        return Arrays.stream(values())
                .map(speciesType -> speciesType.name)
                .anyMatch(specie::equals);
    }

    public static SpeciesType getFromString(String name) {
        return Arrays.stream(values())
                .filter(type -> type.name.equalsIgnoreCase(name))
                .findAny()
                .orElse(BAR);
    }

}
