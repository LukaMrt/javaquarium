package fr.lukam.javaquarium.model.components;

import com.badlogic.ashley.core.Component;

public class AgeComponent implements Component {

    public int age = 0;

    public boolean isTen() {
        return age == 10;
    }

    public void addYear() {
        ++age;
    }

    public boolean isOver(int age) {
        return this.age >= age;
    }

    public int updateAge() {
        int newAge = age % 2 == 0 ? age / 2 : (int) (age / 2 - 0.5);
        age = newAge;
        return newAge;
    }

}
