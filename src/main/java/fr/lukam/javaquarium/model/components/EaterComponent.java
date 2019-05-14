package fr.lukam.test.model.components;

import com.badlogic.ashley.core.Component;
import fr.lukam.test.model.eaters.Eater;

public class EaterComponent implements Component {

    public final Eater eater;

    public EaterComponent(Eater eater) {
        this.eater = eater;
    }

}
