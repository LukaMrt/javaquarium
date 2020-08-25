package fr.lukam.javaquarium.domain.model.fish;

public class FishName {

    private final String name;

    public FishName(String name) {
        this.name = name;
    }

    @Override
    public boolean equals(Object o) {
        FishName fishName = (FishName) o;
        return name.equals(fishName.name);
    }

    @Override
    public String toString() {
        return name;
    }

}
