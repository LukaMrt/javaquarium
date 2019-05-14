package fr.lukam.javaquarium.model.systems;
import com.badlogic.ashley.core.Entity;
import com.badlogic.ashley.core.Family;
import com.badlogic.ashley.systems.IteratingSystem;
import fr.lukam.javaquarium.model.components.EatenComponent;
import fr.lukam.javaquarium.model.components.EaterComponent;
import fr.lukam.javaquarium.model.systems.computers.EatComputer;

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
