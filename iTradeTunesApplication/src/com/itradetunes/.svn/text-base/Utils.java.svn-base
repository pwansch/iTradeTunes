package com.itradetunes;

import java.io.File;
import java.io.IOException;
import java.util.ArrayList;
import java.util.HashSet;
import java.util.List;
import java.util.Locale;
import java.util.MissingResourceException;
import java.util.ResourceBundle;
import java.util.Set;
import java.util.logging.FileHandler;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.util.logging.SimpleFormatter;

public final class Utils implements Constants {
    // Class variables
    private static FileHandler handler = null;
    private static File logFile = null;
    private static ResourceBundle resources = null;
    private static boolean isMac = false;
    private static boolean isWindows = false;

    // Unlocalized messages
    final static String LOGGER_EXCEPTION = "Exception";
    final static String LOGGER_ENTRY_ADDED = "Log entry added: ";

    static {
	// Determine once if we run on OS X or Windows
	String osName = System.getProperty("os.name");

	if (osName.startsWith("Windows")) {
	    isWindows = true;
	} else if (osName.startsWith("Mac OS X")) {
	    isMac = true;
	}
    }

    public final static boolean isWindows() {
	return isWindows;
    }

    public final static boolean isMac() {
	return isMac;
    }

    public final static void initializeLogger() throws IOException {
	// Create a file handler that writes log records to a file
	String fileNameLog = null;
	if (Utils.isMac) {
	    fileNameLog = "Library/Logs/" + APPLICATION_NAME + VERSION_MAJOR + VERSION_MINOR + VERSION_MODIFICATION + ".log";
	} else {
	    fileNameLog = APPLICATION_NAME + VERSION_MAJOR + VERSION_MINOR + VERSION_MODIFICATION + ".log";
	}

	logFile = new File(System.getProperty("user.home"), fileNameLog);
	handler = new FileHandler(logFile.getAbsolutePath());
	handler.setFormatter(new SimpleFormatter());

	// Initialize global logger and add file handler
	Logger.getLogger(Logger.GLOBAL_LOGGER_NAME).addHandler(handler);
    }

    public final static File getLogFile() {
	return logFile;
    }

    public final static void logSevere(Throwable thrown) {
	Logger.getLogger(Logger.GLOBAL_LOGGER_NAME).log(Level.SEVERE, LOGGER_EXCEPTION, thrown);
    }

    public final static void logWarning(String msg) {
	Logger.getLogger(Logger.GLOBAL_LOGGER_NAME).log(Level.WARNING, msg);
    }

    public final static void logInfo(String msg) {
	Logger.getLogger(Logger.GLOBAL_LOGGER_NAME).log(Level.INFO, msg);
    }

    public final static void closeLogger() {
	try {
	    handler.close();
	} catch (SecurityException se) {
	    // Ignore and do not log
	}
    }

    public final static ResourceBundle getResources(String baseName) throws MissingResourceException {
	if (resources == null) {
	    resources = ResourceBundle.getBundle(baseName, Locale.getDefault());
	}
	return resources;
    }

    public final static String getResourceString(String res) {
	String str;
	try {
	    str = resources.getString(res);
	} catch (MissingResourceException mre) {
	    logSevere(mre);
	    str = null;
	}
	return str;
    }

    public final static Character getResourceCharacter(String res) {
	Character ch;
	try {
	    ch = new Character(resources.getString(res).charAt(0));
	} catch (MissingResourceException mre) {
	    // If the resource is not found return null
	    Utils.logSevere(mre);
	    ch = null;
	}
	return ch;
    }

    public final static File[] findFile(String name, File startDir) {
	List<File> result = new ArrayList<File>();
	traverseDirectory(name, startDir, result);
	return result.toArray(new File[result.size()]);
    }

    public final static File[] findFile(String name) {
	Set<File> resultSet = new HashSet<File>();

	File[] roots = File.listRoots();
	for (int i = 0; i < roots.length; i++) {
	    List<File> result = new ArrayList<File>();
	    traverseDirectory(name, roots[i], result);
	    resultSet.addAll(result);
	}
	return resultSet.toArray(new File[resultSet.size()]);
    }

    private final static void traverseDirectory(String name, File dir, List<File> resultSet) {
	boolean isDirectory = false;
	File[] children = null;

	try {
	    isDirectory = dir.isDirectory();
	    if (isDirectory) {
		children = dir.listFiles();
		if (children == null) {
		    return;
		}
	    } else if (dir.isFile()) {
		if (!dir.canRead()) {
		    return;
		}
	    } else {
		return;
	    }
	} catch (SecurityException se) {
	    logSevere(se);
	    return;
	}

	if (isDirectory) {
	    for (int i = 0; i < children.length; i++) {
		traverseDirectory(name, children[i], resultSet);
	    }
	} else {
	    // Check if the filename is right and if the file can be read
	    if (dir.getName().equals(name)) {
		resultSet.add(dir);
	    }
	}
    }

    public final static String removeTrailingChar(String string, char ch) {
	String res = string;

	if (res != null) {
	    int last = res.lastIndexOf(ch);
	    if (last != -1) {
		res = res.substring(0, last);
	    }
	}

	return res;
    }
}