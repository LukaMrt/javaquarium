package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;
import fr.lukam.javaquarium.model.reproducers.Reproducer;

public class ReproducerComponent implements Component {

    public final Reproducer reproducer;

    public ReproducerComponent(Reproducer reproducer) {
        this.reproducer = reproducer;
    }

}
