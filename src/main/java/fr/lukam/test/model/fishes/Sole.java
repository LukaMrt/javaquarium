package fr.lukam.test.model.fishes;

import fr.lukam.test.model.components.SpeciesComponent;
import fr.lukam.test.model.eaters.Eater;
import fr.lukam.test.model.eaters.Herbivorous;
import fr.lukam.test.model.reproducers.Hermaphrodite;
import fr.lukam.test.model.reproducers.Reproducer;

public class Sole implements Fish {

    @Override
    public Eater getEater() {
        return new Herbivorous();
    }

    @Override
    public Reproducer getReproducer() {
        return new Hermaphrodite();
    }

    @Override
    public SpeciesComponent.SpeciesType getSpeciesType() {
        return SpeciesComponent.SpeciesType.SOLE;
    }

}
