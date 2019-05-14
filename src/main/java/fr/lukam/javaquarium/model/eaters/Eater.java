package fr.lukam.javaquarium.model.eaters;

import com.badlogic.ashley.core.Component;
import com.badlogic.ashley.core.Entity;

public interface Eater extends Component {

    boolean canEat(Entity entity);

    int getHealthWon();

    int getHealthInflicted();

}
