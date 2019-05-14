package fr.lukam.test.model.systems;
import com.badlogic.ashley.core.Entity;
import com.badlogic.ashley.core.Family;
import com.badlogic.ashley.systems.IteratingSystem;
import fr.lukam.test.model.components.*;
import fr.lukam.test.model.systems.computers.EatComputer;

public class EatSystem extends IteratingSystem {

    public EatSystem() {
        super(Family.all(EaterComponent.class).get());
    }

    @Override
    protected void processEntity(Entity entity, float deltaTime) {

        if (entity.getComponent(EatenComponent.class) == null) {
            new EatComputer(entity).compute(getEngine());
        }

    }

}
