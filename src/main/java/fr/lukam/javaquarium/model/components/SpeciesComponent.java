package fr.lukam.test.model.components;

import com.badlogic.ashley.core.Component;
import fr.lukam.test.model.fishes.*;

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
        private final String value;

        SpeciesType(Class<? extends Fish> speciesClass, String value) {
            this.speciesClass = speciesClass;
            this.value = value;
        }

        public static boolean isFish(String str) {
            return "bar carpe poissonclown merou sole thon".contains(str);
        }

        public static SpeciesType getFromString(String str) {
            return Arrays.stream(values())
                    .filter(type -> type.value.equalsIgnoreCase(str))
                    .findAny()
                    .orElse(null);
        }

    }

}
