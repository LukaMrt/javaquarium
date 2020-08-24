package fr.lukam.javaquarium.model.reproductions;

import fr.lukam.javaquarium.interfaces.Reproduction;
import fr.lukam.javaquarium.model.Fish;

public class OneSex implements Reproduction {

    @Override
    public boolean reproduce(Fish fish, Fish otherFish) {
        return false;
    }

}
