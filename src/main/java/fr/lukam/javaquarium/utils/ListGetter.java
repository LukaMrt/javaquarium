package fr.lukam.javaquarium.utils;

import java.util.ArrayList;
import java.util.List;

public class ListGetter<T> {

    public List<T> get(List<Object> fromList) {

        List<T> toList = new ArrayList<>();

        for (Object obj : fromList) {

            /* if (obj.getClass().isAssignableFrom(T)) {
                toList.add((T) obj);
            } */

        }

        return toList;

    }

}
