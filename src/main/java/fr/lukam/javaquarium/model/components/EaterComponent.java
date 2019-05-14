package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;
import fr.lukam.javaquarium.model.eaters.Eater;

public class EaterComponent implements Component {
    public final Eater eater;

    public EaterComponent(Eater eater) {
        this.eater = eater;
    }
}
