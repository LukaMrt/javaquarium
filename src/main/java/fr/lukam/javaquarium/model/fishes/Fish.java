package fr.lukam.javaquarium.model.fishes;

import fr.lukam.javaquarium.model.components.SpeciesComponent;
import fr.lukam.javaquarium.model.eaters.Eater;
import fr.lukam.javaquarium.model.reproducers.Reproducer;

public interface Fish {

    Eater getEater();

    Reproducer getReproducer();

    SpeciesComponent.SpeciesType getSpeciesType();

}
