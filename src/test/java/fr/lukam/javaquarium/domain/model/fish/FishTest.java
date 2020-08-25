package fr.lukam.javaquarium.domain.model.fish;

import org.junit.jupiter.params.ParameterizedTest;
import org.junit.jupiter.params.provider.CsvSource;
import org.junit.jupiter.params.provider.ValueSource;

import static fr.lukam.javaquarium.domain.model.fish.FishBuilder.aFish;
import static org.assertj.core.api.Assertions.assertThat;

class FishTest {

    @ParameterizedTest(name = "Fish toString should be \"{0} {1} {2}\" given {0} as name, {1} as sex and {2} as specie")
    @CsvSource(value = {"Tony,male, merou", "Manu,female,thon", "Bob,female,bar", "John,male,poisson-clown"})
    void toString_givenName_shouldBeThisName(String fishName, String sex, String specie) {

        Fish fish = aFish()
                .withFishName(fishName)
                .withSex(sex)
                .withFishSpecie(specie)
                .build();

        assertThat(fish.toString()).isEqualTo(fishName + " " + sex + " " + specie);
    }

    @ParameterizedTest(name = "isSex should be true when fish has sex {0} and asking for {1}")
    @CsvSource(value = {"male,male", "female,female"})
    void isSex_givenASexAndAskForThisSameSex_shouldBeTrue(String fishSex, String askingSex) {

        Fish fish = aFish()
                .withFishName("Tony")
                .withSex(fishSex)
                .build();

        assertThat(fish.isSex(Sex.of(askingSex))).isTrue();
    }

    @ParameterizedTest(name = "equals should be true when 2 pisces are {0}")
    @ValueSource(strings = {"merou", "thon", "poisson-clown", "sole", "bar", "carpe"})
    void equals_givenSameSpecieTo2Pisces_shouldBeTrue(String fishSpecie) {

        Fish fish = aFish()
                .withFishName("Tony")
                .withFishSpecie(fishSpecie)
                .build();

        assertThat(fish).isEqualTo(aFish()
                .withFishName("")
                .withFishSpecie(fishSpecie)
                .build());
    }

}
