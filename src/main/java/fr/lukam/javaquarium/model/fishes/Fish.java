package fr.lukam.test.model.fishes;

import fr.lukam.test.model.components.SpeciesComponent;
import fr.lukam.test.model.eaters.Eater;
import fr.lukam.test.model.reproducers.Reproducer;

public interface Fish {

    Eater getEater();

    Reproducer getReproducer();

    SpeciesComponent.SpeciesType getSpeciesType();

}
