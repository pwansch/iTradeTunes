package com.itradetunes;

import java.awt.*;
import javax.swing.*;
import java.net.*;

// Inner class for Progress Dialog
public class ProgressWindow extends JWindow implements Constants {
    // Class variables
    private static final long serialVersionUID = 1L;

    // Instance variables
    private ProgressPanel progressPanel;

    public ProgressWindow(Component parent, String message) {
	super((Frame) parent);

	// Set border
	getRootPane().setBorder(BorderFactory.createLineBorder(COLOR_BORDER));

	// Create and add an indeterminate progress panel
	URL iconFile = ClassLoader.getSystemResource(FILENAME_LARGE_ICON);
	Icon icon = new ImageIcon(iconFile);
	JLabel iconLabel = new JLabel(icon);
	iconLabel.setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_CX, STRUT_CY, STRUT_CX));
	getContentPane().add(iconLabel, BorderLayout.NORTH);
	progressPanel = new ProgressPanel(message, null, true, true);
	getContentPane().add(progressPanel, BorderLayout.CENTER);
	getContentPane().add(Box.createVerticalStrut(STRUT_BORDER), BorderLayout.SOUTH);
	getContentPane().add(Box.createHorizontalStrut(STRUT_BORDER), BorderLayout.WEST);
	getContentPane().add(Box.createHorizontalStrut(STRUT_BORDER), BorderLayout.EAST);

	// Set default button
	pack();

	if (parent != null) {
	    setLocationRelativeTo(parent);
	}

	// Set wait cursor and show window
	setCursor(Cursor.getPredefinedCursor(Cursor.WAIT_CURSOR));
	setVisible(true);
    }

    public void closeWindow() {
	setCursor(Cursor.getPredefinedCursor(Cursor.DEFAULT_CURSOR));
	setVisible(false);
	dispose();
    }
}
