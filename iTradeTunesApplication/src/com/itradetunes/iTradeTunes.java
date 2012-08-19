package com.itradetunes;

import java.awt.BorderLayout;
import java.awt.Component;
import java.awt.Dimension;
import java.awt.Frame;
import java.awt.GridBagLayout;
import java.awt.Image;
import java.awt.Rectangle;
import java.awt.Toolkit;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.KeyAdapter;
import java.awt.event.KeyEvent;
import java.awt.event.WindowAdapter;
import java.awt.event.WindowEvent;
import java.io.File;
import java.io.IOException;
import java.net.URL;
import java.util.MissingResourceException;

import javax.swing.BorderFactory;
import javax.swing.Box;
import javax.swing.ButtonGroup;
import javax.swing.ImageIcon;
import javax.swing.JButton;
import javax.swing.JDialog;
import javax.swing.JEditorPane;
import javax.swing.JFileChooser;
import javax.swing.JFrame;
import javax.swing.JMenu;
import javax.swing.JMenuBar;
import javax.swing.JMenuItem;
import javax.swing.JOptionPane;
import javax.swing.JPanel;
import javax.swing.JRadioButton;
import javax.swing.JScrollPane;
import javax.swing.JTable;
import javax.swing.JViewport;
import javax.swing.SwingUtilities;
import javax.swing.UIManager;
import javax.swing.UnsupportedLookAndFeelException;
import javax.swing.border.Border;
import javax.swing.filechooser.FileFilter;

// Mac OS X classes are in the ui.jar file
import com.apple.eawt.*;

public class iTradeTunes extends JFrame implements ActionListener, Constants, Controllable, AboutHandler, PreferencesHandler, QuitHandler {
    // Class variables
    private static UIManager.LookAndFeelInfo[] lafInfo;

    // Instance variables
    private Settings settings;
    private ControlBar controlBar;
    private JScrollPane scrollPane;
    private StatusBar statusBar;
    private JMenuBar menuBar;
    private JMenu fileMenu;
    private JMenuItem fileExit;
    private JMenu editMenu;
    private JMenuItem editPreferences;
    private JMenu helpMenu;
    private JMenuItem helpAbout;
    private JMenuItem helpGetStarted;
    private GetStartedDialog getStartedDialog;
    private Library library;
    private TableController tableController;

    // Find out whether we run the right VM
    static {
	String vers = System.getProperty("java.version");
	if (vers.compareTo(MINIMUM_JAVA_VERSION) < 0) {
	    System.err.println(ERROR_RUNNING_BACKLEVEL + vers);

	    // Exit the JVM
	    System.exit(1);
	}

	try {
	    // Initialize the resources
	    Utils.getResources(RESOURCE_BASENAME);
	} catch (MissingResourceException mre) {
	    System.err.println(ERROR_READING_PROPERTIES);

	    // Exit the JVM
	    System.exit(1);
	}

	try {
	    // Initialize the logger
	    Utils.initializeLogger();
	} catch (Exception le) {
	    System.err.println(ERROR_INITIALIZING_LOGGER);

	    // Exit the JVM
	    System.exit(1);
	}

	// Get look and feels
	lafInfo = UIManager.getInstalledLookAndFeels();
    }

    public iTradeTunes() {
	// Good coding practice
	super();

	// Initialize variables
	settings = new Settings();

	// Set the operating system specific look and feel
	try {
	    String osName = System.getProperty("os.name");
	    for (int i = 0; i < lafInfo.length; i++) {
		if (osName.toUpperCase().startsWith(lafInfo[i].getName().toUpperCase())) {
		    UIManager.setLookAndFeel(lafInfo[i].getClassName());
		    SwingUtilities.updateComponentTreeUI(this);
		}
	    }
	} catch (UnsupportedLookAndFeelException uslfe) {
	    Utils.logSevere(uslfe);
	} catch (ClassNotFoundException cnfe) {
	    Utils.logSevere(cnfe);
	} catch (IllegalAccessException iae) {
	    Utils.logSevere(iae);
	} catch (InstantiationException ie) {
	    Utils.logSevere(ie);
	}

	// Set application icon so that the upcoming dialogs also show the
	// application icon
	URL iconFile = ClassLoader.getSystemResource(FILENAME_SMALL_ICON);
	Image iconImage = new ImageIcon(iconFile).getImage();
	setIconImage(iconImage);

	// Search for the music library file
	ProgressWindow progress = null;
	if (settings.getMusicLibrary() == null || !settings.getMusicLibrary().isFile()) {
	    progress = new ProgressWindow(this, Utils.getResourceString(SETTINGS_SEARCHING_MUSIC_LIBRARY));

	    // Search the home directory first
	    File[] result = Utils.findFile(FILENAME_LIBRARY, new File(System.getProperty("user.home")));
	    progress.closeWindow();

	    // Check first if a music library was found
	    if (result.length == 0) {
		// Music library file was not found
		int answer = JOptionPane.showConfirmDialog(this, Utils.getResourceString(ERROR_NO_MUSIC_LIBRARY), Utils.getResourceString(SETTINGS_TITLE),
			JOptionPane.YES_NO_OPTION);

		if (answer == JOptionPane.YES_OPTION) {
		    // Display a directory file chooser
		    // TODO: display a file dialog for mac
		    JFileChooser files = new JFileChooser();
		    files.setFileFilter(new FileFilter() {
			public boolean accept(File file) {
			    return (file.isDirectory()) ? true : FILENAME_LIBRARY.equals(file.getName());
			}

			public String getDescription() {
			    return Utils.getResourceString(SETTINGS_LIBRARYCHOOSER_DESCRIPTION);
			}
		    });

		    files.setDialogTitle(Utils.getResourceString(SETTINGS_LIBRARYCHOOSER_TITLE));
		    files.setApproveButtonToolTipText(Utils.getResourceString(SETTINGS_LIBRARYCHOOSER_TOOLTIP));
		    files.setApproveButtonMnemonic(Utils.getResourceCharacter(SETTINGS_LIBRARYCHOOSER_MNEMONIC).charValue());
		    files.setFileSelectionMode(JFileChooser.FILES_ONLY);
		    files.setAcceptAllFileFilterUsed(false);

		    int returnVal = files.showDialog(getParent(), Utils.getResourceString(SETTINGS_LIBRARYCHOOSER_APPROVE));
		    if (returnVal == JFileChooser.APPROVE_OPTION) {
			// Get the selected file
			result = new File[] { files.getSelectedFile() };
		    } else {
			exit();
		    }
		} else if (answer == JOptionPane.NO_OPTION) {
		    // Scan the entire system
		    progress = new ProgressWindow(this, Utils.getResourceString(SETTINGS_SEARCHING_ENTIRE_SYSTEM));
		    result = Utils.findFile(FILENAME_LIBRARY);
		    if (result.length == 0) {
			JOptionPane.showMessageDialog(this, Utils.getResourceString(ERROR_NO_ITUNES_MUSIC_LIBRARY), Utils.getResourceString(SETTINGS_TITLE),
				JOptionPane.ERROR_MESSAGE);
			exit();
		    }
		}
	    }

	    File selected = null;
	    if (result.length > 1) {
		// More then one music library file was found on the system
		// Let user select or confirm music library
		selected = (File) JOptionPane.showInputDialog(this, Utils.getResourceString(SETTINGS_SELECT_MUSIC_LIBRARY),
			Utils.getResourceString(SETTINGS_TITLE), JOptionPane.QUESTION_MESSAGE, null, result, result[0]);

		// Check whether user has cancelled the input
		if (selected == null) {
		    JOptionPane.showMessageDialog(this, Utils.getResourceString(ERROR_NO_MUSIC_LIBRARY_SELECTED), Utils.getResourceString(SETTINGS_TITLE),
			    JOptionPane.ERROR_MESSAGE);
		    exit();
		}
	    } else {
		selected = result[0];
	    }
	    settings.setMusicLibrary(selected);

	    // Save settings in case the process terminates
	    settings.saveSettings();
	}

	// Parse the music library and import XML file
	try {
	    progress = new ProgressWindow(this, Utils.getResourceString(SETTINGS_PARSING_MUSIC_LIBRARY));

	    library = new Library(settings.getMusicLibrary());

	} catch (LibraryException le) {
	    settings.setMusicLibrary(null);
	    // Save settings in case the process terminates
	    settings.saveSettings();

	    JOptionPane.showMessageDialog(this, Utils.getResourceString(ERROR_PARSING_MUSIC_LIBRARY), Utils.getResourceString(SETTINGS_TITLE),
		    JOptionPane.ERROR_MESSAGE);
	    exit();
	} finally { // Always close the progress window
	    progress.closeWindow();
	}

	// Create control and status bar
	controlBar = new ControlBar(this);
	statusBar = new StatusBar(this);

	// Set menu bar and add top-level menus to it
	menuBar = new JMenuBar();
	setJMenuBar(menuBar);

	// Create Help menu
	helpGetStarted = new JMenuItem(Utils.getResourceString(MENU_HELP_GETSTARTED));
	helpGetStarted.setMnemonic((Utils.getResourceCharacter(ACCEL_HELP_GETSTARTED)).charValue());
	helpGetStarted.addActionListener(this);
	helpAbout = new JMenuItem(Utils.getResourceString(MENU_HELP_ABOUT));
	helpAbout.setMnemonic((Utils.getResourceCharacter(ACCEL_HELP_ABOUT)).charValue());
	helpAbout.addActionListener(this);
	helpMenu = new JMenu(Utils.getResourceString(MENU_HELP));
	helpMenu.setMnemonic((Utils.getResourceCharacter(ACCEL_HELP)).charValue());
	helpMenu.add(helpGetStarted);
	if (Utils.isWindows()) {
	    helpMenu.addSeparator();
	    helpMenu.add(helpAbout);
	}

	// TODO: Enable control bar buttons accordingly
	controlBar.enableTrade(true);

	// Do not create File menu on Mac
	if (!Utils.isMac()) {
	    fileExit = new JMenuItem(Utils.getResourceString(MENU_FILE_EXIT));
	    fileExit.setMnemonic((Utils.getResourceCharacter(ACCEL_FILE_EXIT)).charValue());
	    fileExit.addActionListener(this);
	    fileMenu = new JMenu(Utils.getResourceString(MENU_FILE));
	    fileMenu.setMnemonic((Utils.getResourceCharacter(ACCEL_FILE)).charValue());
	    fileMenu.add(fileExit);
	}
	// Create Edit menu
	editPreferences = new JMenuItem(Utils.getResourceString(MENU_EDIT_PREFERENCES));
	editPreferences.setMnemonic((Utils.getResourceCharacter(ACCEL_EDIT_PREFERENCES)).charValue());
	editPreferences.addActionListener(this);
	editMenu = new JMenu(Utils.getResourceString(MENU_EDIT));
	editMenu.setMnemonic((Utils.getResourceCharacter(ACCEL_EDIT)).charValue());
	if (!Utils.isMac()) {
	    // Only add the preferences menu for Windows
	    editMenu.add(editPreferences);
	}
	// Add menus
	if (!Utils.isMac()) {
	    menuBar.add(fileMenu);
	}
	menuBar.add(editMenu);
	menuBar.add(helpMenu);

	// Set up handlers for Mac
	if (Utils.isMac()) {
	    Application.getApplication().setAboutHandler(this);
	    Application.getApplication().setPreferencesHandler(this);
	    Application.getApplication().setQuitHandler(this);
	}

	// Add pane to the frame window
	Toolkit theKit = getToolkit();
	Dimension screenSize = theKit.getScreenSize();
	scrollPane = new JScrollPane();
	scrollPane.setPreferredSize(new Dimension(screenSize.width / 4 * 3, screenSize.height / 4 * 2));

	// Create the table controller
	tableController = new TableController(library, controlBar, statusBar, this);

	// Add the table into the scroll pane
	JViewport tableViewport = scrollPane.getViewport();
	tableViewport.setBackground(COLOR_TABLE);
	JTable table = tableController.getTable();
	tableViewport.add(table);

	// Enable or disable the search entry field
	statusBar.enableSearch(library.getCount() > 0);

	// TODO: Set the status bar text
	statusBar.setText("");

	// Add to the content pane layout
	getContentPane().add(controlBar, BorderLayout.NORTH);
	getContentPane().add(scrollPane, BorderLayout.CENTER);
	getContentPane().add(statusBar, BorderLayout.SOUTH);

	// Size the default close operation
	setDefaultCloseOperation(DO_NOTHING_ON_CLOSE);

	// Size the frame window
	pack();

	// Set the frame location
	Rectangle windowSize = getBounds();
	setLocation((screenSize.width - windowSize.width) / 2, (screenSize.height - windowSize.height) / 2);

	// Set the title
	setTitle(Utils.getResourceString(APPLICATION));

	// Add a listener for window events
	addWindowListener(new WindowAdapter() // Begin inner class
	{
	    // Handler for window closing event
	    public void windowClosing(WindowEvent e) {
		exit();
	    }
	});

	// TODO: Sets the focus

	// Set a non-resizable frame and show the frame window
	setVisible(true);

	// Display the get started dialog
	getStartedDialog = null;
	if (settings.isDisplayGetStarted()) {
	    getStartedDialog = new GetStartedDialog(this, Utils.getResourceString(GETSTARTED_TITLE));
	}

    }

    public void handleAbout(AppEvent.AboutEvent event) {
	new AboutDialog(iTradeTunes.this, Utils.getResourceString(ABOUT_TITLE));
    }

    public void handlePreferences(AppEvent.PreferencesEvent event) {
	// Create preferences dialog box with the application window as its
	// parent
	new PreferencesDialog(iTradeTunes.this, Utils.getResourceString(PREFERENCES_TITLE));
    }

    public void handleQuitRequestWith(AppEvent.QuitEvent e, QuitResponse response) {
	exit();
    }

    public void actionPerformed(ActionEvent e) {
	if (e.getSource().equals(helpAbout)) {
	    // Create about dialog box with the application window as its parent
	    new AboutDialog(this, Utils.getResourceString(ABOUT_TITLE));
	} else if (e.getSource().equals(helpGetStarted)) {
	    // Create getStarted dialog box with the app window as its parent
	    if (getStartedDialog != null) {
		getStartedDialog.closeWindow();
	    }
	    getStartedDialog = new GetStartedDialog(this, Utils.getResourceString(GETSTARTED_TITLE));
	} else if (e.getSource().equals(editPreferences)) {
	    // Create preferences dialog box with the app window as its parent
	    new PreferencesDialog(this, Utils.getResourceString(PREFERENCES_TITLE));

	} else if (e.getSource().equals(fileExit)) {
	    exit();
	}

    }

    boolean exit() {
	// Dispose the application window
	dispose();

	// Save settings
	settings.saveSettings();

	// Close the logger file handler
	Utils.closeLogger();

	// Exit the JVM
	System.exit(0);
	return true;
    }

    public void tradeSongs() {
	// TODO: Transfer songs
    }

    public void filter(String str) {
	// Filter the beans result
	if (tableController != null) {
	    JTable table = tableController.getTable();
	    if (table != null) {
		TableModel tableModel = (TableModel) table.getModel();
		tableModel.match(str);
	    }
	}
    }

    public Component getParentComponent() {
	return this;
    }

    public void updateStatusBar() {
	String message = null;

	// TODO: Set the status message
	// Set the status message
	statusBar.setText(message != null ? message : "");
    }

    public static void main(String[] args) {
	// Create an application instance
	new iTradeTunes();
    }

    // Inner class for About Dialog
    class AboutDialog extends JDialog implements ActionListener {
	private JEditorPane editorPane;
	private JPanel editorPanels;
	private JButton okButton;

	public AboutDialog(Frame parent, String title) {
	    super(parent, title, true); // Make this a modal dialog box

	    // Create the URL for the about text file
	    URL aboutFile = ClassLoader.getSystemResource(FILENAME_ABOUT);

	    try {
		editorPanels = new JPanel(new BorderLayout());

		// Create the editor pane, load the text file and size it
		editorPane = new JEditorPane(aboutFile);
		editorPane.setPreferredSize(DIMENSION_ABOUT);
		editorPane.setEditable(false);
		Border edge = BorderFactory.createEtchedBorder();
		editorPane.setBorder(edge);
		editorPanels.add(editorPane, BorderLayout.NORTH);

	    } catch (IOException ioe) {
		// The URL cannot be accessed, bring up error message box
		Utils.logSevere(ioe);
		JOptionPane.showMessageDialog(this, Utils.getResourceString(ERROR_FILE_NOT_LOADED), Utils.getResourceString(ABOUT_TITLE),
			JOptionPane.ERROR_MESSAGE);
		return;
	    }

	    // Add border struts and pane
	    getContentPane().add(Box.createVerticalStrut(STRUT_BORDER), BorderLayout.NORTH);
	    getContentPane().add(editorPanels, BorderLayout.CENTER);
	    getContentPane().add(Box.createHorizontalStrut(STRUT_BORDER), BorderLayout.WEST);
	    getContentPane().add(Box.createHorizontalStrut(STRUT_BORDER), BorderLayout.EAST);

	    // Create the button pane and add the buttons
	    JPanel buttonPane = new JPanel();
	    okButton = new JButton(Utils.getResourceString(BUTTON_OK));
	    buttonPane.add(okButton);
	    okButton.addActionListener(this);
	    getContentPane().add(buttonPane, BorderLayout.SOUTH);

	    // Add a key listener to close the window when Enter is pressed to
	    // override
	    // the default behavior of the editor panel
	    editorPane.addKeyListener(new KeyAdapter() {
		public void keyPressed(KeyEvent e) {
		    if (e.getKeyCode() == KeyEvent.VK_ENTER) {
			closeWindow();
		    }
		}
	    });

	    // Set default button
	    getRootPane().setDefaultButton(okButton);
	    setDefaultCloseOperation(DISPOSE_ON_CLOSE);
	    pack();

	    if (parent != null) {
		setLocationRelativeTo(parent);
	    }

	    // Show window
	    setResizable(false);
	    setVisible(true);
	}

	public void actionPerformed(ActionEvent e) {
	    if (e.getSource().equals(okButton)) {
		closeWindow();
		return;
	    }
	}

	void closeWindow() {
	    setVisible(false);
	    dispose();
	}
    }

    // Inner class for GetStarted Dialog
    class GetStartedDialog extends JDialog implements ActionListener {
	private JEditorPane editorPane;
	private JRadioButton displayAtStartup;
	private JRadioButton displayNever;

	public GetStartedDialog(Frame parent, String title) {
	    super(parent, title, false); // Make this a modeless dialog box

	    // Create the URL for the about text file
	    URL aboutFile = ClassLoader.getSystemResource(FILENAME_GETSTARTED);

	    // Create the editor pane, load the text file and size it
	    try {
		JPanel editorPanels = new JPanel(new BorderLayout());
		editorPane = new JEditorPane(aboutFile);
		editorPane.setEditable(false);
		editorPane.setBorder(BorderFactory.createEtchedBorder());
		JScrollPane scrollPane = new JScrollPane(editorPane);
		scrollPane.setPreferredSize(DIMENSION_GETSTARTED);
		scrollPane.setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_BORDER, 0, STRUT_BORDER));
		editorPanels.add(scrollPane, BorderLayout.NORTH);
		getContentPane().add(editorPanels, BorderLayout.NORTH);
	    } catch (IOException ioe) {
		// The URL cannot be accessed, bring up error message box
		Utils.logSevere(ioe);
		JOptionPane.showMessageDialog(this, Utils.getResourceString(ERROR_FILE_NOT_LOADED), Utils.getResourceString(ABOUT_TITLE),
			JOptionPane.ERROR_MESSAGE);
		return;
	    }

	    // Add radio buttons
	    JPanel radioPane = new JPanel(new BorderLayout());
	    displayAtStartup = new JRadioButton(Utils.getResourceString(GETSTARTED_DISPLAY_STARTUP), settings.isDisplayGetStarted());
	    radioPane.add(displayAtStartup, BorderLayout.NORTH);
	    displayNever = new JRadioButton(Utils.getResourceString(GETSTARTED_DISPLAY_NEVER), !settings.isDisplayGetStarted());
	    radioPane.add(displayNever, BorderLayout.SOUTH);
	    radioPane.setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_BORDER, 0, STRUT_BORDER));
	    getContentPane().add(radioPane, BorderLayout.CENTER);
	    ButtonGroup radioGroup = new ButtonGroup();
	    radioGroup.add(displayAtStartup);
	    radioGroup.add(displayNever);

	    // Create the button pane
	    JPanel buttonPane = new JPanel();
	    JButton button = new JButton(Utils.getResourceString(BUTTON_OK));
	    buttonPane.add(button);
	    button.addActionListener(this);
	    getContentPane().add(buttonPane, BorderLayout.SOUTH);

	    // Add a key listener to close the window when Enter is pressed to
	    // override
	    // the default behavior of the editor panel
	    editorPane.addKeyListener(new KeyAdapter() {
		public void keyPressed(KeyEvent e) {
		    if (e.getKeyCode() == KeyEvent.VK_ENTER) {
			closeWindow();
		    }
		}
	    });

	    // Set default button
	    getRootPane().setDefaultButton(button);
	    setDefaultCloseOperation(DISPOSE_ON_CLOSE);
	    pack();

	    if (parent != null) {
		setLocationRelativeTo(parent);
	    }

	    // Show window
	    setResizable(false);
	    setVisible(true);
	}

	public void actionPerformed(ActionEvent e) {
	    settings.setDisplayGetStarted(displayAtStartup.isSelected());
	    // Save settings in case the process terminates
	    settings.saveSettings();
	    closeWindow();
	}

	void closeWindow() {
	    setVisible(false);
	    dispose();
	}
    }

    // Inner class for Preferences dialog
    class PreferencesDialog extends JDialog implements ActionListener {
	// Controls for general pane

	// Buttons
	private JButton okButton;
	private JButton cancelButton;

	public PreferencesDialog(Frame parent, String title) {
	    // Make this a modal dialog box
	    super(parent, title, true);

	    // Initialize

	    // Build the general pane
	    JPanel generalPane = buildGeneralPane();

	    // Create the button pane and add to the bottom panel
	    JPanel buttonPanel = new JPanel();
	    buttonPanel.add(okButton = new JButton(Utils.getResourceString(BUTTON_OK)));
	    buttonPanel.add(cancelButton = new JButton(Utils.getResourceString(BUTTON_CANCEL)));

	    // Add the tabbed pane
	    getContentPane().add(Box.createHorizontalStrut(STRUT_BORDER), BorderLayout.WEST);
	    getContentPane().add(Box.createHorizontalStrut(STRUT_BORDER), BorderLayout.EAST);
	    getContentPane().add(Box.createVerticalStrut(STRUT_BORDER), BorderLayout.NORTH);
	    getContentPane().add(generalPane, BorderLayout.CENTER);
	    getContentPane().add(buttonPanel, BorderLayout.SOUTH);

	    // Add action listener
	    okButton.addActionListener(this);
	    cancelButton.addActionListener(this);

	    // Set default button
	    getRootPane().setDefaultButton(okButton);
	    setDefaultCloseOperation(DISPOSE_ON_CLOSE);
	    pack();

	    if (parent != null) {
		setLocationRelativeTo(parent);
	    }

	    // Show window
	    setResizable(false);
	    setVisible(true);
	}

	private JPanel buildGeneralPane() {
	    // Create a general pane
	    JPanel generalPane = new JPanel(new GridBagLayout());
	    generalPane.setBackground(COLOR_DIALOG);

	    // Create the controls

	    // Set the border
	    generalPane.setBorder(BorderFactory.createEmptyBorder(STRUT_BORDER, STRUT_BORDER, STRUT_BORDER, STRUT_BORDER));

	    // Add action listener

	    return generalPane;
	}

	public void actionPerformed(ActionEvent e) {
	    if (e.getSource().equals(okButton)) {
		// TODO: Save preferences
	    }

	    // Save the settings in case the process gets terminated
	    settings.saveSettings();

	    // Close and dispose the window
	    setVisible(false);
	    dispose();
	}

    }
}
