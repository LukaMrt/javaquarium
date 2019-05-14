package fr.lukam.test.model.fishes;

import fr.lukam.test.model.components.SpeciesComponent;
import fr.lukam.test.model.eaters.Eater;
import fr.lukam.test.model.eaters.Herbivorous;
import fr.lukam.test.model.reproducers.AgeHermaphrodite;
import fr.lukam.test.model.reproducers.Reproducer;

public class Bar implements Fish {

    @Override
    public Eater getEater() {
        return new Herbivorous();
    }

    @Override
    public Reproducer getReproducer() {
        return new AgeHermaphrodite();
    }

    @Override
    public SpeciesComponent.SpeciesType getSpeciesType() {
        return SpeciesComponent.SpeciesType.BAR;
    }

}
