package com.itradetunes;

import javax.swing.*;
import javax.swing.event.*;
import javax.swing.text.*;
import java.awt.*;
import java.awt.event.*;
import java.net.*;

public class ControlBar extends JPanel implements ActionListener, Constants {
    // Instance variables
    private JButton tradeButton;
    private JLabel tradeLabel;
    private Controllable controllable;

    public ControlBar(Controllable controllable) {
	// Set a grid bag layout
	super(new GridBagLayout());

	// Initialize
	this.controllable = controllable;

	// Set opaque to false so that the background is not redrawn
	setOpaque(false);

	// Only change the background color for Windows
	if (Utils.isWindows()) {
	    setBackground(COLOR_CONTROLBAR);
	}

	// Add trade button and label
	URL iconFile = ClassLoader.getSystemResource(FILENAME_BUTTON_TRADE_ICON);
	Icon icon = new ImageIcon(iconFile);
	tradeButton = new JButton(icon);
	tradeButton.setContentAreaFilled(false);
	tradeButton.addActionListener(this);
	tradeButton.setBorderPainted(false);
	tradeButton.setOpaque(false);
	GridBagConstraints gbc = new GridBagConstraints();
	gbc.gridx = 0;
	gbc.gridy = 0;
	gbc.fill = GridBagConstraints.NONE;
	gbc.gridwidth = 1;
	gbc.gridheight = 1;
	gbc.weightx = 1.0;
	gbc.weighty = 1.0;
	gbc.anchor = GridBagConstraints.CENTER;
	add(tradeButton, gbc);
	tradeLabel = new JLabel(Utils.getResourceString(CONTROLBAR_LABEL_TRADE), JLabel.CENTER);
	gbc.gridx = 0;
	gbc.gridy = 1;
	gbc.fill = GridBagConstraints.NONE;
	gbc.gridwidth = 1;
	gbc.gridheight = 1;
	gbc.weightx = 1.0;
	gbc.weighty = 1.0;
	gbc.anchor = GridBagConstraints.CENTER;
	add(tradeButton, gbc);

	// Set border
	setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_BORDER, STRUT_BORDER, STRUT_BORDER));

	// Set tooltips
	tradeButton.setToolTipText(Utils.getResourceString(TOOLTIP_BUTTON_TRADE));

	// Enable dragging the control bar
	addMouseMotionListener(new MouseDraggedListener(controllable.getParentComponent()));
    }

    public void actionPerformed(ActionEvent e) {
	if (e.getSource().equals(tradeButton)) {
	    // Trade songs
	    controllable.tradeSongs();
	}
    }

    public void enableTrade(boolean enabled) {
	tradeButton.setEnabled(enabled);
	tradeLabel.setEnabled(enabled);
    }

    public void paintComponent(Graphics g) {
	// Only change the paint for Windows
	if (Utils.isWindows()) {
	    Graphics2D g2D = (Graphics2D) g;
	    // Paint the window background with gradient default color
	    Dimension dimClient = getSize();
	    GradientPaint gradPaint = new GradientPaint(0, dimClient.height / 2, getBackground().brighter(), dimClient.width, dimClient.height / 2,
		    getBackground(), false);
	    g2D.setPaint(gradPaint);
	    g2D.fill(new Rectangle(dimClient));
	}
	super.paintComponent(g);
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