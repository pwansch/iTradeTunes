package com.itradetunes;

import java.awt.*;
import javax.swing.*;
import javax.swing.plaf.basic.BasicArrowButton;
import javax.swing.table.*;

public class SortedHeaderRenderer implements TableCellRenderer, Constants {
    // Instance variables
    private TableModel tableModel;
    private int sortColumn;
    private boolean sortAscending;
    private static final String UITableHeaderBackground = "TableHeader.background"; //$NON-NLS-1$
    private static final String UITableHeaderForeground = "TableHeader.foreground"; //$NON-NLS-1$
    private static final String UITableHeaderFont = "TableHeader.font"; //$NON-NLS-1$
    private static final String UITableHeaderCellBorder = "TableHeader.cellBorder"; //$NON-NLS-1$

    public SortedHeaderRenderer(TableModel tableModel) {
	// Initialize instance variables
	this.tableModel = tableModel;
	sortColumn = -1;
	sortAscending = true;
    }

    public Component getTableCellRendererComponent(JTable table, Object value, boolean isSelected, boolean hasFocus, int row, int column) {
	JPanel panel = new JPanel(new BorderLayout());
	Component text = new JLabel((String) value, JLabel.LEFT);
	LookAndFeel.installColorsAndFont((JComponent) text, UITableHeaderBackground, UITableHeaderForeground, UITableHeaderFont);
	panel.add(text, BorderLayout.CENTER);
	if (column == sortColumn) {
	    BasicArrowButton bab = new BasicArrowButton((sortAscending ? SwingConstants.NORTH : SwingConstants.SOUTH));
	    bab.setBorder(BorderFactory.createEmptyBorder(0, 0, 0, 0));
	    panel.add(bab, BorderLayout.WEST);
	}

	LookAndFeel.installBorder(panel, UITableHeaderCellBorder);
	return panel;
    }

    public void columnSelected(int column) {
	if (column != sortColumn) {
	    sortColumn = column;
	    sortAscending = true;
	} else {
	    sortAscending = !sortAscending;
	    if (sortAscending) {
		sortColumn = -1;
	    }
	}
	if (sortColumn != -1) {
	    tableModel.sort(sortColumn, sortAscending);
	} else {
	    tableModel.clearSort();
	}
    }
}