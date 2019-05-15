package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;

public class SpeciesComponent implements Component {

    public final SpeciesType speciesType;

    public SpeciesComponent(SpeciesType speciesType) {
        this.speciesType = speciesType;
    }

}
