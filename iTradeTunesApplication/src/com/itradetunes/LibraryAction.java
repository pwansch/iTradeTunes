package com.itradetunes;

import java.awt.event.*;
import javax.swing.*;

public abstract class LibraryAction extends AbstractAction {
    private static final String ACCELERATOR_KEY = "ACCELERATOR_KEY";
    private static final String MNEMONIC_KEY = "MNEMONIC_KEY";

    public LibraryAction(String name, KeyStroke keystroke, Character key, String tooltip, Icon icon) {
	// Call constructor to set the action description string
	super(name);

	// Set accelerator key
	if (keystroke != null) {
	    putValue(ACCELERATOR_KEY, keystroke);
	}

	// Set mnemonic key code
	if (key != null) {
	    putValue(MNEMONIC_KEY, key);
	}

	// Set tooltip
	if (tooltip != null) {
	    putValue(SHORT_DESCRIPTION, tooltip);
	}

	// Set icon
	if (icon != null) {
	    putValue(SMALL_ICON, icon);
	}

	// Set disabled by default
	setEnabled(false);
    }

    public abstract void actionPerformed(ActionEvent e);

    public JMenuItem addToMenu(JMenu menu) {
	// Add the action to the menu
	JMenuItem item = menu.add(this);

	// Set accelerator for menu item if set
	KeyStroke keystroke = (KeyStroke) getValue(ACCELERATOR_KEY);
	if (keystroke != null) {
	    item.setAccelerator(keystroke);
	}

	// Set mnemonic for menu item if set
	Character mnemonic = (Character) getValue(MNEMONIC_KEY);
	if (mnemonic != null) {
	    item.setMnemonic(mnemonic.charValue());
	}

	String tooltip = (String) getValue(SHORT_DESCRIPTION);
	if (tooltip != null) {
	    item.setToolTipText(tooltip);
	}

	// We don't want icons displayed in menus
	item.setIcon(null);

	return item;
    }
}