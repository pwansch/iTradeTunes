package com.itradetunes;

import java.io.*;
import java.util.prefs.*;

class Settings implements Serializable, Constants {
    // Constants
    private static Preferences prefs = Preferences.userNodeForPackage(com.itradetunes.iTradeTunes.class);

    // Instance variables
    private File musicLibrary;
    private File currentDirectoryPath;
    private boolean displayGetStarted;

    public Settings() {
	// Initialize
	try {
	    musicLibrary = new File(prefs.get("musicLibrary", null));
	} catch (NullPointerException npe) {
	    musicLibrary = null;
	}
	try {
	    currentDirectoryPath = new File(prefs.get("currentDirectoryPath", null));
	} catch (NullPointerException npe) {
	    currentDirectoryPath = null;
	}
	displayGetStarted = prefs.getBoolean("displayGetStarted", true);
    }

    public final void setMusicLibrary(File file) {
	musicLibrary = file;
	prefs.put("musicLibrary", file.getAbsolutePath());
    }

    public final void setCurrentDirectoryPath(File newCurrentDirectoryPath) {
	currentDirectoryPath = newCurrentDirectoryPath;
	prefs.put("currentDirectoryPath", newCurrentDirectoryPath.getAbsolutePath());
    }

    public void setDisplayGetStarted(boolean newDisplayGetStarted) {
	displayGetStarted = newDisplayGetStarted;
	prefs.putBoolean("displayGetStarted", newDisplayGetStarted);
    }

    public final File getMusicLibrary() {
	return musicLibrary;
    }

    public final File getCurrentDirectoryPath() {
	return currentDirectoryPath;
    }

    public boolean isDisplayGetStarted() {
	return displayGetStarted;
    }

    public final void saveSettings() {
	try {
	    // Try to flush
	    prefs.flush();
	} catch (BackingStoreException bse) {
	    // Just ignore and continue as it will be for not enough space or
	    // similar reasons we cannot recover from
	}
    }
}