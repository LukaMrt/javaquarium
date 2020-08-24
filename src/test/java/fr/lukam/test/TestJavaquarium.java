package fr.lukam.test;

import com.badlogic.ashley.core.Engine;
import fr.lukam.javaquarium.model.components.*;
import fr.lukam.javaquarium.model.entityadders.FishAdder;
import fr.lukam.javaquarium.model.entityadders.SeaweedAdder;
import fr.lukam.javaquarium.model.fishes.Carpe;
import fr.lukam.javaquarium.model.fishes.Merou;
import fr.lukam.javaquarium.model.fishes.Sole;
import fr.lukam.javaquarium.model.systems.EatSystem;
import fr.lukam.javaquarium.model.systems.ReproduceSystem;
import fr.lukam.javaquarium.model.systems.UpdateSystem;
import org.junit.Before;
import org.junit.Test;

import static org.assertj.core.api.AssertionsForClassTypes.assertThat;

public class TestJavaquarium {

    private Engine engine;

    @Before
    public void setUp() {
        this.engine = new Engine();
    }

    @Test
    public void test_name_sex() {
        new FishAdder("test1", SexComponent.SexType.MALE, new Merou()).addToEngine(engine);
        new FishAdder("test2", SexComponent.SexType.FEMALE, new Sole()).addToEngine(engine);

        assertThat(engine.getEntities().get(0).getComponent(NameComponent.class).name).isEqualTo("test1");
        assertThat(engine.getEntities().get(1).getComponent(NameComponent.class).name).isEqualTo("test2");

        assertThat(engine.getEntities().get(0).getComponent(SexComponent.class).sex).isEqualTo(SexComponent.SexType.MALE);
        assertThat(engine.getEntities().get(1).getComponent(SexComponent.class).sex).isEqualTo(SexComponent.SexType.FEMALE);

        assertThat(engine.getEntities().get(0).getComponent(SpeciesComponent.class).specie).isEqualTo(SpeciesType.MEROU);
        assertThat(engine.getEntities().get(1).getComponent(SpeciesComponent.class).specie).isEqualTo(SpeciesType.SOLE);
    }

    @Test
    public void test_age_health() {
        new FishAdder("test2", SexComponent.SexType.FEMALE, new Carpe()).addToEngine(engine);
        new SeaweedAdder(1).addToEngine(engine);
        engine.addSystem(new UpdateSystem());

        engine.update(1);

        assertThat(engine.getEntities().get(0).getComponent(AgeComponent.class).age).isEqualTo(1);
        assertThat(engine.getEntities().get(1).getComponent(AgeComponent.class).age).isEqualTo(1);

        assertThat(engine.getEntities().get(0).getComponent(HealthComponent.class).health).isEqualTo(9);
        assertThat(engine.getEntities().get(1).getComponent(HealthComponent.class).health).isEqualTo(2);
    }

    @Test
    public void test_eat() {
        new FishAdder("1", SexComponent.SexType.MALE, new Sole()).addToEngine(engine);
        new FishAdder("2", SexComponent.SexType.MALE, new Sole()).addToEngine(engine);
        new FishAdder("3", SexComponent.SexType.MALE, new Sole()).addToEngine(engine);
        new SeaweedAdder(1).addToEngine(engine);
        engine.addSystem(new UpdateSystem());
        engine.addSystem(new EatSystem());

        for (int i = 0; i < 10; i++) {
            engine.update(1);
        }

        assertThat(engine.getEntities().size()).isEqualTo(3);
    }

    @Test
    public void test_eat_2() {
        System.out.println(engine.getEntities().size());
        new FishAdder("merou", SexComponent.SexType.MALE, new Merou()).addToEngine(engine);
        new FishAdder("sole", SexComponent.SexType.MALE, new Sole()).addToEngine(engine);
        new SeaweedAdder(4).addToEngine(engine);
        System.out.println(engine.getEntities().size());

        engine.addSystem(new UpdateSystem());
        engine.addSystem(new EatSystem());

        for (int i = 0; i < 5; i++) {
            engine.update(1);
        }

        assertThat(engine.getEntities().get(0).getComponent(HealthComponent.class).health).isEqualTo(10);
        assertThat(engine.getEntities().get(1).getComponent(HealthComponent.class).health).isEqualTo(1);
    }

    @Test
    public void test_reproduce() {
        new FishAdder("merou", SexComponent.SexType.MALE, new Carpe()).addToEngine(engine);
        new FishAdder("sole", SexComponent.SexType.FEMALE, new Carpe()).addToEngine(engine);

        engine.addSystem(new UpdateSystem());
        engine.addSystem(new ReproduceSystem());

        engine.update(1);

        assertThat(engine.getEntities().size()).isEqualTo(4);
    }

    @Test
    public void test_reproduce_2() {
        new FishAdder("merou", SexComponent.SexType.MALE, new Sole()).addToEngine(engine);
        new FishAdder("sole", SexComponent.SexType.FEMALE, new Sole()).addToEngine(engine);

        engine.addSystem(new UpdateSystem());
        engine.addSystem(new ReproduceSystem());

        engine.update(1);

        System.out.println(engine.getEntities().size());
        assertThat(engine.getEntities().size()).isEqualTo(4);
    }

}
