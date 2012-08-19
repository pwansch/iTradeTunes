package com.itradetunes;

import java.awt.*;
import java.awt.event.*;

public class MouseDraggedListener extends MouseMotionAdapter {
    private Component component;
    private int x;
    private int y;

    public MouseDraggedListener(Component component) {
	// Initialize
	super();

	// Initialize variables
	this.component = component;
	x = 0;
	y = 0;
    }

    public void mouseMoved(MouseEvent evt) {
	x = evt.getX();
	y = evt.getY();
    }

    public void mouseDragged(MouseEvent evt) {
	component.setLocation(component.getX() + evt.getX() - x, component.getY() + evt.getY() - y);
    }
}