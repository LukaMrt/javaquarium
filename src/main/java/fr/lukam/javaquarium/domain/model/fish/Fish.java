package fr.lukam.javaquarium.domain.model.fish;

public class Fish {

    private final FishName fishName;
    private final Sex sex;
    private final Specie fishSpecie;

    public Fish(FishBuilder builder) {
        this.fishName = builder.getFishName();
        this.sex = builder.getSex();
        this.fishSpecie = builder.getFishSpecie();
    }

    public boolean isSex(Sex otherSex) {
        return this.sex == otherSex;
    }

    @Override
    public boolean equals(Object o) {
        Fish fish = (Fish) o;
        return fishSpecie == fish.fishSpecie;
    }

    @Override
    public String toString() {
        return fishName + " " + sex + " " + fishSpecie;
    }

}
