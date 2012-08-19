package com.itradetunes;

import javax.swing.table.*;
import java.beans.*;
import java.util.*;

class TableModel extends AbstractTableModel implements Constants {
    // Instance variables
    private TableController tableController;
    private AlbumBean[] beans;
    private int rowCount;
    private int columnCount;
    private String[] columnNames;
    private int sortColumn;
    private boolean sortAscending;

    public TableModel(TableController tableController) {
	// Good coding practice
	super();

	// Initialize fields
	this.tableController = tableController;
	beans = null;
	rowCount = 0;
	columnCount = 0;
	columnNames = null;
	sortColumn = -1;
	sortAscending = false;
	if (tableController != null) {
	    beans = tableController.getBeans();
	    if (beans != null) {
		rowCount = beans.length;

		// Get the visible column data
		String[] visibleProperties = AlbumBean.getVisibleProperties();
		if (visibleProperties != null) {
		    columnCount = visibleProperties.length;
		    columnNames = new String[columnCount];
		    for (int i = 0; i < columnNames.length; i++) {
			columnNames[i] = Character.toUpperCase(visibleProperties[i].charAt(0)) + visibleProperties[i].substring(1);
		    }
		}
	    }
	}
    }

    public void refresh() {
	// Match with the currently selected values
	match(tableController.getStr());
    }

    public void refreshRow(int rowIndex) {
	// Initialize the beans again, the count has not changed
	beans = tableController.getBeans();
	if (sortColumn != -1) {
	    // Also sort by column
	    sort(sortColumn, sortAscending);
	}

	// Update the specific row
	fireTableRowsUpdated(rowIndex, rowIndex);
    }

    public void match(String str) {
	// Set the str
	tableController.setStr(str);

	// Initialize the beans again
	beans = tableController.getBeans();
	rowCount = (beans != null) ? beans.length : 0;

	if (sortColumn == -1) {
	    // Indicate that the data has changed
	    fireTableDataChanged();
	} else {
	    // Also sort by column
	    sort(sortColumn, sortAscending);
	}
    }

    public void clearSort() {
	// Reset the sort fields and initialize the beans again
	sortColumn = -1;
	sortAscending = false;
	beans = tableController.getBeans();

	// Indicate that the data has changed
	fireTableDataChanged();
    }

    public void sort(int column, boolean ascending) {
	if (beans != null) {
	    // Sort the beans array
	    Arrays.sort(beans, AlbumBean.getBeanSorter(columnNames[column], ascending));

	    // Indicate that the data has changed
	    fireTableDataChanged();
	}
    }

    // Override the following methods
    public int getRowCount() {
	return rowCount;
    }

    public int getColumnCount() {
	return columnCount;
    }

    public AlbumBean getBeanAt(int rowIndex) {
	AlbumBean result = null;
	try {
	    result = beans[rowIndex];
	} catch (ArrayIndexOutOfBoundsException exception) {
	    // Return null
	}

	return result;
    }

    public Object getValueAt(int rowIndex, int columnIndex) {
	Object value = null;
	Expression expr = new Expression(beans[rowIndex], "get" + columnNames[columnIndex], new Object[0]);
	try {
	    expr.execute();
	    value = expr.getValue();

	} catch (Exception e) {
	    // Ignore, cannot occur
	    Utils.logSevere(e);
	}

	return value;
    }
    
    public void setValueAt(Object value, int rowIndex, int columnIndex) {	
	Object[] values = new Object[1];
	values[0] = value;
	Expression expr = new Expression(beans[rowIndex], "set" + columnNames[columnIndex], values);
	try {
	    expr.execute();

	} catch (Exception e) {
	    // Ignore, cannot occur
	    Utils.logSevere(e);
	}
        fireTableCellUpdated(rowIndex, rowIndex);
    }    

    @SuppressWarnings({ "rawtypes", "unchecked" })
    public Class getColumnClass(int column) {
	return AlbumBean.getColumnClass(column);
    }

    public String getColumnName(int column) {
	return AlbumBean.getColumnName(column);
    }

    public boolean isCellEditable(int row, int column) {
	if (column == 0 || column == 1) {
	    return true;
	} else {
	    return false;
	}
    }
}