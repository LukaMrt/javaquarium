package fr.lukam.javaquarium.model.fishes;

import fr.lukam.javaquarium.model.eaters.Carnivorous;
import fr.lukam.javaquarium.model.eaters.Eater;
import fr.lukam.javaquarium.model.components.SpeciesComponent;
import fr.lukam.javaquarium.model.reproducers.Hermaphrodite;
import fr.lukam.javaquarium.model.reproducers.Reproducer;

public class PoissonClown implements Fish {

    @Override
    public Eater getEater() {
        return new Carnivorous();
    }

    @Override
    public Reproducer getReproducer() {
        return new Hermaphrodite();
    }

    @Override
    public SpeciesComponent.SpeciesType getSpeciesType() {
        return SpeciesComponent.SpeciesType.POISSONCLOWN;
    }

}
