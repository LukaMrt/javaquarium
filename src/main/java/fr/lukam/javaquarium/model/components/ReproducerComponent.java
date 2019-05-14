package fr.lukam.test.model.components;

import com.badlogic.ashley.core.Component;
import fr.lukam.test.model.reproducers.Reproducer;

public class ReproducerComponent implements Component {

    public final Reproducer reproducer;

    public ReproducerComponent(Reproducer reproducer) {
        this.reproducer = reproducer;
    }

}
