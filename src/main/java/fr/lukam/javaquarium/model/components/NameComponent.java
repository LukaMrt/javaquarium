package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;

public class NameComponent implements Component {

    public final String name;

    public NameComponent(String name) {
        this.name = name;
    }

}
