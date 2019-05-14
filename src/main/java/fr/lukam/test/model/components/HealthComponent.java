package fr.lukam.test.model.components;

import com.badlogic.ashley.core.Component;

public class HealthComponent implements Component {

    public int health;

    private final int healthPerTour;

    public HealthComponent(int health, int healthPerTour) {
        this.health = health;
        this.healthPerTour = healthPerTour;
    }

    public void update() {
        this.health += this.healthPerTour;
    }

}
