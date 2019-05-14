package fr.lukam.test.model.components;

import com.badlogic.ashley.core.Component;

import java.util.Arrays;
import java.util.Random;

public class SexComponent implements Component {

    public SexType sex;

    public SexComponent(SexType sex) {
        this.sex = sex;
    }

    public enum SexType {

        MALE, FEMALE;

        public SexType getOtherSex() {
            return this == MALE ? FEMALE : MALE;
        }

        public static SexType getRandom() {
            return Arrays.asList(MALE, FEMALE).get(new Random().nextInt(2));
        }

    }

}
