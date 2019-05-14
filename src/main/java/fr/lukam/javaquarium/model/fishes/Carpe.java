package fr.lukam.javaquarium.model.fishes;

import fr.lukam.javaquarium.model.components.SpeciesComponent;
import fr.lukam.javaquarium.model.eaters.Eater;
import fr.lukam.javaquarium.model.eaters.Herbivorous;
import fr.lukam.javaquarium.model.reproducers.OneSex;
import fr.lukam.javaquarium.model.reproducers.Reproducer;

public class Carpe implements Fish {

    @Override
    public Eater getEater() {
        return new Herbivorous();
    }

    @Override
    public Reproducer getReproducer() {
        return new OneSex();
    }

    @Override
    public SpeciesComponent.SpeciesType getSpeciesType() {
        return SpeciesComponent.SpeciesType.CARPE;
    }

}
