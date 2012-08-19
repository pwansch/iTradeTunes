package com.itradetunes;

import javax.swing.*;
import javax.swing.Timer;
import java.awt.*;
import java.awt.event.*;

public class ProgressPanel extends JPanel implements Constants {
    // Constants
    private static final String CARD_PROGRESS = "CARD_PROGRESS";
    private static final String CARD_ICON = "CARD_ICON";

    // Instance variables
    private JProgressBar pbar;
    private CardLayout card;
    private JLabel label;
    private Timer delay;

    public ProgressPanel(String labelText, Icon icon, boolean progress, boolean labelOnTop) {
	// Set layout and opaqueness
	card = new CardLayout();
	setLayout(card);
	setOpaque(false);

	// Create progress pane
	JPanel progressPane = new JPanel(new BorderLayout());
	progressPane.setOpaque(false);

	// Initialize instance variables
	pbar = new JProgressBar();
	delay = null;

	// Get space for the string but don't paint
	pbar.setStringPainted(false);
	pbar.setString("");
	progressPane.add(pbar, labelOnTop ? BorderLayout.CENTER : BorderLayout.NORTH);

	// Create the text label
	label = new JLabel(labelText, JLabel.CENTER);
	if (labelOnTop) {
	    label.setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_BORDER, STRUT_BORDER, STRUT_BORDER));
	}
	progressPane.add(label, labelOnTop ? BorderLayout.NORTH : BorderLayout.SOUTH);

	// Add the progress pane to the card layout
	add(progressPane, CARD_PROGRESS);

	// Create and add the icon label
	JLabel iconLabel = new JLabel(icon);
	add(iconLabel, CARD_ICON);

	// Display the initial card
	showProgress(progress, true);
    }

    public void setMinMaxValue(int min, int max, int value) {
	pbar.setMinimum(min);
	pbar.setMaximum(max);
	pbar.setValue(value);
    }

    public void setLabel(String labelText) {
	label.setText(labelText);
    }

    public void setString(String string) {
	if (string != null) {
	    pbar.setStringPainted(true);
	    pbar.setString(string);
	} else {
	    pbar.setStringPainted(false);
	    pbar.setString(null);
	}
    }

    public void showProgress(boolean progress, boolean bouncing) {
	if (progress) {
	    pbar.setIndeterminate(bouncing);
	    card.show(this, CARD_PROGRESS);
	    if (delay != null) {
		delay.stop();
		delay = null;
	    }
	} else {
	    ActionListener taskPerformer = new ActionListener() {
		public void actionPerformed(ActionEvent evt) {
		    setString(null);
		    pbar.setIndeterminate(false);
		    card.show(ProgressPanel.this, CARD_ICON);
		}
	    };

	    if (delay != null) {
		delay.stop();
		delay = null;
	    }

	    // Start a delayed timer
	    delay = new Timer(PROGRESS_STOP_DELAY, taskPerformer);
	    delay.setRepeats(false);
	    delay.start();
	}
    }
}