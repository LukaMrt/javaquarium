package fr.lukam.test.model.fishes;

import fr.lukam.test.model.components.SpeciesComponent;
import fr.lukam.test.model.eaters.Carnivorous;
import fr.lukam.test.model.eaters.Eater;
import fr.lukam.test.model.reproducers.AgeHermaphrodite;
import fr.lukam.test.model.reproducers.Reproducer;

public class Merou implements Fish {

    @Override
    public Eater getEater() {
        return new Carnivorous();
    }

    @Override
    public Reproducer getReproducer() {
        return new AgeHermaphrodite();
    }

    @Override
    public SpeciesComponent.SpeciesType getSpeciesType() {
        return SpeciesComponent.SpeciesType.MEROU;
    }

}
