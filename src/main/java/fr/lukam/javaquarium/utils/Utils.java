package fr.lukam.javaquarium.utils;

import fr.lukam.javaquarium.enums.Fishes;
import fr.lukam.javaquarium.enums.Sex;
import fr.lukam.javaquarium.model.Aquarium;
import fr.lukam.javaquarium.model.Entity;
import fr.lukam.javaquarium.model.Fish;

import java.util.ArrayList;
import java.util.List;
import java.util.Random;

import static fr.lukam.javaquarium.enums.Fishes.*;

public class Utils {

    public static Sex getOtherSex(Sex sex) {

        switch (sex) {

            case MALE:
                return Sex.FEMALE;

            case FEMALE:
                return Sex.MALE;

            default:
                return null;

        }

    }

    public static Fishes getFishEnum(String str) {

        switch (str) {

            case "bar":
                return BAR;

            case "carpe":
                return CARPE;

            case "poissonclown":
                return CLOWNFISH;

            case "merou":
                return MEROU;

            case "sole":
                return SOLE;

            case "thon":
                return THON;

            default:
                return null;

        }

    }

    public static Fishes getFishEnum(Class<? extends Entity> aClass) {

        switch (aClass.getName().substring(aClass.getName().lastIndexOf(".") + 1)) {

            case "Bar":
                return BAR;

            case "Carpe":
                return CARPE;

            case "ClownFish":
                return CLOWNFISH;

            case "Merou":
                return MEROU;

            case "Sole":
                return SOLE;

            case "Thon":
                return THON;

            default:
                return null;

        }

    }

    public static boolean isFish(String str) {

        switch (str) {

            case "bar":
                return true;

            case "carpe":
                return true;

            case "poissonclown":
                return true;

            case "merou":
                return true;

            case "sole":
                return true;

            case "thon":
                return true;

            default:
                return false;

        }

    }

    public static Fish getRandomFish() {

        List<Fish> fishes = new ArrayList<>(Aquarium.getInstance().getishes());

        return fishes.isEmpty() ? null : fishes.get(new Random().nextInt(fishes.size()));

    }

    public static List<Object> getObjectsFromList(List<Entity> fromList, Class aClass) {

        List<Object> toList = new ArrayList<>();

        for (Entity obj : fromList) {

            if (obj.getClass().isAssignableFrom(aClass)) {
                toList.add(obj);
            }

        }

        return toList;

    }

}
