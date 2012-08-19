package com.itradetunes;

import javax.swing.*;
import javax.swing.event.*;
import javax.swing.table.JTableHeader;
import javax.swing.table.TableCellRenderer;
import javax.swing.table.TableColumn;
import java.awt.*;
import java.awt.event.*;
import java.util.ArrayList;
import java.util.List;

public class TableController implements LibraryListener, Constants, ListSelectionListener {
    // Instance variables
    protected Library library;
    protected AlbumBean[] result;
    protected TableModel tableModel;
    protected JTable table;
    protected String str;
    protected JPopupMenu popupMenu;
    protected ControlBar controlBar;
    protected StatusBar statusBar;
    protected Controllable controllable;
    protected List<LibraryAction> menu;
    protected LibraryAction selectAll;
    private LibraryAction transferSongs;

    public TableController(Library library, ControlBar controlBar, StatusBar statusBar, Controllable controllable) {
	// Initialize the variables
	this.library = library;
	this.controlBar = controlBar;
	this.statusBar = statusBar;
	this.controllable = controllable;
	result = library.getResult();
	tableModel = new TableModel(this);
	table = new JTable(tableModel);
	str = null;
	popupMenu = new JPopupMenu();
	menu = new ArrayList<LibraryAction>();

	// Initialize popups
	selectAll = new SelectAllAction(Utils.getResourceString(POPUP_SELECT_ALL), KeyStroke.getKeyStroke(SHORTCUT_SELECT_ALL, Toolkit.getDefaultToolkit()
		.getMenuShortcutKeyMask()));
	selectAll.setEnabled(library.getCount() > 0);

	// Initialize menu
	menu.add(selectAll);

	// Set the selection mode
	table.setSelectionMode(ListSelectionModel.MULTIPLE_INTERVAL_SELECTION);

	// Add table selection listener
	table.getSelectionModel().addListSelectionListener(this);

	// Add listener for popup menu
	table.addMouseListener(new MouseAdapter() {
	    public void mousePressed(MouseEvent evt) {
		if (evt.isPopupTrigger()) {
		    popupMenu.show(evt.getComponent(), evt.getX(), evt.getY());
		}
	    }

	    public void mouseReleased(MouseEvent evt) {
		if (evt.isPopupTrigger()) {
		    popupMenu.show(evt.getComponent(), evt.getX(), evt.getY());
		}
	    }

	    public void mouseClicked(MouseEvent evt) {
		// Check for double-clicks
		if (evt.getClickCount() == 2) {
		    doubleClicked(evt);
		}
	    }
	});

	// Set the table renderers
	setTableRenderersAndWidth();

	// Set the look and feel of the table
	table.setAutoResizeMode(JTable.AUTO_RESIZE_ALL_COLUMNS);
	table.setGridColor(COLOR_TREE);
	table.setShowHorizontalLines(false);
	table.setShowVerticalLines(false);
	table.setIntercellSpacing(new Dimension(0, 2));

	// Initialize popup
	popupMenu.addSeparator();
	transferSongs = new TransferSongsAction(Utils.getResourceString(POPUP_TRANSFER), KeyStroke.getKeyStroke(SHORTCUT_TRANSFER, Toolkit.getDefaultToolkit()
		.getMenuShortcutKeyMask()));
	popupMenu.add(transferSongs);

	// Initialize menu
	menu.add(transferSongs);
    }

    public void refresh() {
	// Refresh the library
	result = library.getResult();

	// Refresh the model
	tableModel.refresh();
    }

    private void setTableRenderersAndWidth() {
	// Get the sorted column header renderer
	final SortedHeaderRenderer columnRenderer = AlbumBean.getColumnHeaderRenderer(tableModel);

	// Set the column renderers and column width
	double width = (Toolkit.getDefaultToolkit().getScreenSize().getWidth() * 0.8) / 4 * 3;
	int columnCount = AlbumBean.getVisibleProperties().length;
	for (int i = 0; i < columnCount; i++) {
	    TableColumn col = table.getColumnModel().getColumn(i);

	    // Set sortable header renderers
	    col.setHeaderRenderer(columnRenderer);

	    // Set custom cell renderers if available
	    TableCellRenderer cellRenderer = AlbumBean.getTableCellRenderer(AlbumBean.getVisibleProperties()[i]);
	    if (cellRenderer != null) {
		col.setCellRenderer(cellRenderer);
	    }

	    // Set column width
	    col.setPreferredWidth((int) (width * AlbumBean.getRelativeColumnWidth(i) / 100));
	}

	// Add the header listeners
	table.getTableHeader().addMouseListener(new MouseInputAdapter() {
	    public void mouseClicked(MouseEvent event) {
		JTableHeader header = (JTableHeader) event.getSource();
		int index = header.columnAtPoint(event.getPoint());
		columnRenderer.columnSelected(index);
		if (index != -1) {
		    table.setColumnSelectionInterval(index, index);
		}
	    }
	});
    }

    public void valueChanged(ListSelectionEvent lse) {
	// The list selection has changed
    }

    public AlbumBean[] getBeans() {
	// Clone and return results
	if (result != null) {
	    List<AlbumBean> beanList = new ArrayList<AlbumBean>();
	    for (int i = 0; i < result.length; i++) {
		if (str == null || result[i].matches(str)) {
		    beanList.add(result[i]);
		}
	    }

	    return beanList.toArray(new AlbumBean[beanList.size()]);
	}

	return new AlbumBean[0];
    }

    public void libraryChanged(LibraryChangeEvent lce) {
	// If the library has been reset, refresh it and also the tabs
	if (lce.getType() == LibraryChangeEvent.UPDATE) {
	    // Update the rows that have changed
	    AlbumBean[] beansChanged = lce.getEntries();
	    for (int i = 0; i < beansChanged.length; i++) {
		int row = 0;
		for (row = 0; row < tableModel.getRowCount(); row++) {
		    AlbumBean bean = tableModel.getBeanAt(row);
		    if (bean != null && bean.equals(beansChanged[i])) {
			break;
		    }
		}

		if (row < tableModel.getRowCount()) {
		    // The row was found
		    result = library.getResult();
		    tableModel.refreshRow(row);
		}
	    }
	} else {
	    // Refresh in all other cases
	    refresh();
	}

	// Enable or disable the search entry field and menu items
	statusBar.enableSearch(library.getCount() > 0);
	selectAll.setEnabled(library.getCount() > 0);

	// Update the status bar
	controllable.updateStatusBar();
    }

    protected void doubleClicked(MouseEvent evt) {
	// The associated table has been double-clicked, ignore
    }

    public void transferSongs() {
    }

    // Inner classes for actions
    class TransferSongsAction extends LibraryAction {
	// Constructor
	TransferSongsAction(String name, KeyStroke accelerator) {
	    // Call constructor to set action properties
	    super(name, accelerator, null, null, null);
	}

	// Event handler
	public void actionPerformed(ActionEvent e) {
	    // Transfer songs
	    transferSongs();
	}
    }

    public JTable getTable() {
	return table;
    }

    public void setStr(String string) {
	str = string;
    }

    public String getStr() {
	return str;
    }

    // Inner class for select all
    class SelectAllAction extends LibraryAction {
	// Constructor
	SelectAllAction(String name, KeyStroke accelerator) {
	    // Call constructor to set action properties
	    super(name, accelerator, null, null, null);
	}

	// Event handler
	public void actionPerformed(ActionEvent e) {
	    table.selectAll();
	}
    }

}