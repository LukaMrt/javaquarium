package fr.lukam.javaquarium.model.fishes;

import fr.lukam.javaquarium.model.components.SpeciesType;
import fr.lukam.javaquarium.model.eaters.Eater;
import fr.lukam.javaquarium.model.reproducers.Reproducer;

public interface Fish {

    Eater getEater();

    Reproducer getReproducer();

    SpeciesType getSpeciesType();

}
