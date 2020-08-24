package fr.lukam.javaquarium.model;

import java.util.List;
import java.util.Random;

public abstract class Entity {

    private int lifePoint = 10;
    private int age = new Random().nextInt(10);

    public int getLifePoint() {
        return lifePoint;
    }

    public void addLifePoint(int newLifePoint) {
        this.lifePoint += newLifePoint;
    }

    public int getAge() {
        return age;
    }

    protected void addAge() {
        this.age++;
    }

    public abstract Entity eat(List<Entity> entities);

    public abstract void age();

    public abstract Entity reproduce();

}
