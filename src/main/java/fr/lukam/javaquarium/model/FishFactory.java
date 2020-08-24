package fr.lukam.javaquarium.model;

import fr.lukam.javaquarium.enums.Fishes;
import fr.lukam.javaquarium.enums.Sex;
import fr.lukam.javaquarium.model.builders.FishBuilder;
import fr.lukam.javaquarium.model.eaters.Carnivore;
import fr.lukam.javaquarium.model.eaters.Herbivore;
import fr.lukam.javaquarium.model.fishes.*;
import fr.lukam.javaquarium.model.reproductions.AgeHermaphrodite;
import fr.lukam.javaquarium.model.reproductions.OneSex;
import fr.lukam.javaquarium.model.reproductions.OpportunityHermaphrodite;

import java.util.Arrays;
import java.util.List;
import java.util.Random;

public class FishFactory {

    public Fish getFish(String name, Fishes fishType) {

        List<Sex> sexes = Arrays.asList(Sex.MALE, Sex.FEMALE);
        Sex sex = sexes.get(new Random().nextInt(sexes.size()));

        switch (fishType) {

            case BAR:
                return new Bar(FishBuilder.aFish()
                        .withName(name)
                        .withSex(sex)
                        .withEater(new Herbivore())
                        .withSexChanger(new AgeHermaphrodite())
                        .build());

            case CARPE:
                return new Carpe(FishBuilder.aFish()
                        .withName(name)
                        .withSex(sex)
                        .withEater(new Herbivore())
                        .withSexChanger(new OneSex())
                        .build());

            case CLOWNFISH:
                return new ClownFish(FishBuilder.aFish()
                        .withName(name)
                        .withSex(sex)
                        .withEater(new Carnivore())
                        .withSexChanger(new OpportunityHermaphrodite())
                        .build());

            case MEROU:
                return new Merou(FishBuilder.aFish()
                        .withName(name)
                        .withSex(sex)
                        .withEater(new Carnivore())
                        .withSexChanger(new AgeHermaphrodite())
                        .build());

            case SOLE:
                return new Sole(FishBuilder.aFish()
                        .withName(name)
                        .withSex(sex)
                        .withEater(new Herbivore())
                        .withSexChanger(new OpportunityHermaphrodite())
                        .build());

            case THON:
                return new Thon(FishBuilder.aFish()
                        .withName(name)
                        .withSex(sex)
                        .withEater(new Carnivore())
                        .withSexChanger(new OneSex())
                        .build());

            default:
                return null;

        }

    }

}
