package com.itradetunes;

import javax.swing.*;
import javax.swing.border.*;
import javax.swing.event.*;
import javax.swing.text.*;
import java.awt.*;

public class StatusBar extends JPanel implements Constants {
    // Instance variables
    private JTextField searchField;
    private JLabel searchLabel;
    private JLabel statusBar;
    private Controllable controllable;

    public StatusBar(Controllable controllable) {
	// Set a grid bag layout
	super(new BorderLayout());

	// Initialize
	this.controllable = controllable;

	// Set the background on Windows only
	if (Utils.isWindows()) {
	    setBackground(new Color(166, 202, 240));
	}

	// Build the search pane
	searchField = new JTextField(20);
	searchField.setEnabled(false);
	searchField.setBorder(new EtchedBorder(getBackground().brighter(), getBackground().darker()));
	searchField.getDocument().addDocumentListener(new TextChangeListener());
	searchLabel = new JLabel(new ImageIcon(ClassLoader.getSystemResource(FILENAME_SEARCH_ICON)), JLabel.RIGHT);
	searchLabel.setEnabled(false);

	JPanel searchPane = new JPanel(new FlowLayout(FlowLayout.LEFT));
	searchPane.setOpaque(true);
	if (Utils.isWindows()) {
	    searchPane.setBackground(new Color(166, 202, 240));
	}
	searchPane.add(searchLabel);
	searchPane.add(searchField);

	// Build the status bar
	statusBar = new JLabel("", JLabel.CENTER);
	statusBar.setOpaque(false);

	// Add the panes
	add(searchPane, BorderLayout.WEST);
	add(statusBar, BorderLayout.CENTER);

	// Set border
	setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_BORDER, STRUT_BORDER, STRUT_BORDER));

	// Set tooltips
	searchField.setToolTipText(Utils.getResourceString(TOOLTIP_BUTTON_SEARCH));

	// Enable dragging the status bar
	addMouseMotionListener(new MouseDraggedListener(controllable.getParentComponent()));
    }

    public void setText(String text) {
	statusBar.setText(text);
    }

    public void enableSearch(boolean enabled) {
	searchField.setEnabled(enabled);
	searchLabel.setEnabled(enabled);
    }

    class TextChangeListener implements DocumentListener {
	// This method is called after an insert into the document
	public void insertUpdate(DocumentEvent evt) {
	    process(evt);
	}

	// This method is called after a removal from the document
	public void removeUpdate(DocumentEvent evt) {
	    process(evt);
	}

	// This method is called after one or more attributes have changed
	public void changedUpdate(DocumentEvent evt) {
	    // Ignore
	}

	private void process(DocumentEvent evt) {

	    // Get inserted string
	    Document document = evt.getDocument();
	    int length = document.getLength();

	    try {
		String str = document.getText(0, length);
		controllable.filter(str);
	    } catch (BadLocationException e) {
		// Ignore, but log
		Utils.logSevere(e);
	    }
	}
    }
}