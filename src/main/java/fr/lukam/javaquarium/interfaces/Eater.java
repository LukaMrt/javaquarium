package fr.lukam.javaquarium.interfaces;

import fr.lukam.javaquarium.model.Entity;

import java.util.List;

public interface Eater {

    Entity eat(List<Entity> entities);

}
