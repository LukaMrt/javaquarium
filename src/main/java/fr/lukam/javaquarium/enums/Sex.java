package fr.lukam.javaquarium.enums;

public enum Sex {

    MALE("mâle"),
    FEMALE("femelle");

    private final String french;

    Sex(String french) {
        this.french = french;
    }

    public String getFrench() {
        return french;
    }
}
