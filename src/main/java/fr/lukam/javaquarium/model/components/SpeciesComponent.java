package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;

public class SpeciesComponent implements Component {

    public final SpeciesType specie;

    public SpeciesComponent(SpeciesType specie) {
        this.specie = specie;
    }

    public boolean is(SpeciesType specie) {
        return this.specie == specie;
    }

}
