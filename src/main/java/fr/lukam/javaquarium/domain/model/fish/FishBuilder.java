package fr.lukam.javaquarium.domain.model.fish;

public class FishBuilder {

    private FishName fishName;
    private Sex sex = Sex.random();
    private Specie fishSpecie = Specie.random();

    private FishBuilder() {
    }

    public static FishBuilder aFish() {
        return new FishBuilder();
    }

    public FishBuilder withFishName(FishName fishName) {
        this.fishName = fishName;
        return this;
    }

    public FishBuilder withSex(Sex sex) {
        this.sex = sex;
        return this;
    }

    public FishBuilder withFishSpecie(Specie fishSpecie) {
        this.fishSpecie = fishSpecie;
        return this;
    }

    public FishBuilder withFishName(String fishName) {
        this.fishName = new FishName(fishName);
        return this;
    }

    public FishBuilder withSex(String sex) {
        this.sex = Sex.of(sex);
        return this;
    }

    public FishBuilder withFishSpecie(String fishSpecie) {
        this.fishSpecie = Specie.of(fishSpecie);
        return this;
    }

    public Fish build() {
        return new Fish(this);
    }

    public FishName getFishName() {
        return fishName;
    }

    public Sex getSex() {
        return sex;
    }

    public Specie getFishSpecie() {
        return fishSpecie;
    }

}
