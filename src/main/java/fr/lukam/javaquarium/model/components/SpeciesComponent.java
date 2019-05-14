package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;
import fr.lukam.javaquarium.model.fishes.*;

import java.util.Arrays;

public class SpeciesComponent implements Component {

    public final SpeciesType speciesType;

    public SpeciesComponent(SpeciesType speciesType) {
        this.speciesType = speciesType;
    }

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

        public static boolean isFish(String testString) {
            return Arrays.stream(values())
                    .map(speciesType -> speciesType.name)
                    .reduce((allNames, name) -> allNames += name)
                    .map(allNames -> allNames.contains(testString))
                    .get();
        }

        public static SpeciesType getFromString(String name) {
            return Arrays.stream(values())
                    .filter(type -> type.name.equalsIgnoreCase(name))
                    .findAny()
                    .orElse(null);
        }

    }

}
