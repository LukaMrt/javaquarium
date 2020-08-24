package fr.lukam.javaquarium.model;

import fr.lukam.javaquarium.enums.Fishes;
import fr.lukam.javaquarium.utils.Utils;

import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;
import java.util.Random;

import static fr.lukam.javaquarium.enums.Fishes.*;

public class Aquarium {

    private static Aquarium instance;

    private FishFactory fishFactory = new FishFactory();

    private int tour = 0;
    private List<Entity> entities = new ArrayList<>();

    private List<Entity> eaten = new ArrayList<>();
    private List<Fish> babies = new ArrayList<>();

    public static Aquarium getInstance() {
        if (instance == null) {
            instance = new Aquarium();
        }
        return instance;
    }

    public List<Entity> getEntities() {
        return this.entities;
    }

    public List<Fish> getishes() {

        List<Fish> fishes = new ArrayList<>();

        for (Entity entity : this.entities) {

            if (entity instanceof Fish) {

                fishes.add((Fish) entity);

            }

        }

        return fishes;

    }

    public int getTour() {
        return tour;
    }

    public void addFish(String name, Fishes fishType) {

        this.babies.add(fishFactory.getFish(name, fishType));

    }

    public void addFish(String name) {

        Random r = new Random();

        List<Fishes> fishes = Arrays.asList(BAR,
                CARPE,
                CLOWNFISH,
                MEROU,
                SOLE,
                THON);

        addFish(name, fishes.get(r.nextInt(fishes.size())));

    }

    public void addSeaweed() {
        this.entities.add(new Seaweed());
    }

    public void tour() {

        this.tour++;

        int random = new Random().nextInt(1);

        for (int i = 0; i <= random; i++) {
            addSeaweed();
        }

        List<Entity> entities = new ArrayList<>(this.entities);

        while (!entities.isEmpty()) {

            Entity entity = entities.get(new Random().nextInt(entities.size()));

            entity.age();

            if (entity.getAge() >= 20 || entity.getLifePoint() <= 0) {
                this.entities.remove(entity);
                entities.remove(entity);
                continue;
            }

            if (!this.eaten.contains(entity)) {

                if (entity.getLifePoint() >= 5) {

                    Entity eatenEntity = entity.eat(this.entities);

                    if (eatenEntity != null) {
                        this.eaten.add(eatenEntity);
                    }

                } else {

                    if (entity instanceof Fish) {

                        Entity otherEntity = entity.reproduce();

                        if (otherEntity != null) {

                            if (entity.getClass().equals(otherEntity.getClass())
                                    && !((Fish) entity).getSex().equals(((Fish) otherEntity).getSex())) {

                                addFish("bebe", Utils.getFishEnum(entity.getClass()));

                            }

                        }

                    }

                }

            }

            entities.remove(entity);

        }

        this.entities.addAll(this.babies);
        this.babies = new ArrayList<>();

        this.eaten = new ArrayList<>();

    }

}
