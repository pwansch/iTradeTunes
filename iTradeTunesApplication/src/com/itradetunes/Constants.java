package com.itradetunes;

import java.awt.Color;
import java.awt.Dimension;
import java.awt.event.KeyEvent;

public interface Constants {

    // Version constants
    final short VERSION_MAJOR = 1;
    final short VERSION_MINOR = 0;
    final short VERSION_MODIFICATION = 0;
    final String MINIMUM_JAVA_VERSION = "1.5.0";

    // Miscellaneous constants
    final String APPLICATION_NAME = "iTradeTunes";
    final int PROGRESS_STOP_DELAY = 1000;
    final String FORMAT_DATE_UTC = "yyyy-MM-dd'T'HH:mm:ss";
    final String ALBUMBEAN_PREFIX = "ALBUMBEAN_";
    final int MINIMUM_NUMBER_OF_TRACKS = 1;
    final int MAXIMUM_NUMBER_OF_TRACKS = 99;
    final int MAXIMUM_NUMBER_OF_DISCS = 10;

    // User interface constants
    final int STRUT_BORDER = 10;
    final int STRUT_CX = 2;
    final int STRUT_CY = 2;
    final Color COLOR_BORDER = Color.DARK_GRAY;
    final Color COLOR_DIALOG = Color.WHITE;
    final Color COLOR_CONTROLBAR = new Color(166, 202, 240);
    final Color COLOR_STATUSBAR = new Color(166, 202, 240);
    final Color COLOR_TREE = Color.WHITE;
    final Color COLOR_TABLE = Color.WHITE;
    final Dimension DIMENSION_ABOUT = new Dimension(520, 335);
    final Dimension DIMENSION_GETSTARTED = new Dimension(500, 270);
    final int SHORTCUT_TRANSFER = KeyEvent.VK_T;
    final int SHORTCUT_SELECT_ALL = KeyEvent.VK_A;

    // Miscellaneous filenames
    final String RESOURCE_BASENAME = "resources.itradetunes";
    final String FILENAME_LIBRARY = "iTunes Music Library.xml";
    final String FILENAME_ABOUT = "resources/about.html";
    final String FILENAME_GETSTARTED = "resources/getstarted.html";
    final String FILENAME_SMALL_ICON = "resources/images/logo_small.gif";
    final String FILENAME_LARGE_ICON = "resources/images/logo_large.gif";
    final String FILENAME_BUTTON_TRADE_ICON = "resources/images/trade.png";
    final String FILENAME_SEARCH_ICON = "resources/images/magnifier.png";
    final String FILENAME_PROPERTY_LIST = "resources/PropertyList-1.0.dtd";
    final String URL_PROPERTY_LIST = "http://www.apple.com/DTDs/PropertyList-1.0.dtd";

    // Unlocalized messages
    final String ERROR_RUNNING_BACKLEVEL = "Unable to run in a backlevel virtual machine: ";
    final String ERROR_READING_PROPERTIES = "Unable to read properties.";
    final String ERROR_INITIALIZING_LOGGER = "Unable to initialize logger.";

    // Localized properties
    final String APPLICATION = "APPLICATION";

    // Menus
    final String MENU_HELP = "MENU_HELP";
    final String MENU_HELP_ABOUT = "MENU_HELP_ABOUT";
    final String MENU_HELP_GETSTARTED = "MENU_HELP_GETSTARTED";
    final String MENU_FILE = "MENU_FILE";
    final String MENU_FILE_EXIT = "MENU_FILE_EXIT";
    final String MENU_EDIT = "MENU_EDIT";
    final String MENU_EDIT_PREFERENCES = "MENU_EDIT_PREFERENCES";

    // Popup menus
    final String POPUP_TRANSFER = "POPUP_TRANSFER";
    final String POPUP_SELECT_ALL = "POPUP_SELECT_ALL";

    // Accelerators
    final String ACCEL_HELP = "ACCEL_HELP";
    final String ACCEL_HELP_ABOUT = "ACCEL_HELP_ABOUT";
    final String ACCEL_HELP_GETSTARTED = "ACCEL_HELP_GETSTARTED";
    final String ACCEL_FILE = "ACCEL_FILE";
    final String ACCEL_FILE_EXIT = "ACCEL_FILE_EXIT";
    final String ACCEL_EDIT = "ACCEL_EDIT";
    final String ACCEL_EDIT_PREFERENCES = "ACCEL_EDIT_PREFERENCES";

    // Dialog texts
    final String BUTTON_OK = "BUTTON_OK";
    final String BUTTON_CANCEL = "BUTTON_CANCEL";
    final String ERROR_FILE_NOT_LOADED = "ERROR_FILE_NOT_LOADED";
    final String CONTROLBAR_LABEL_TRADE = "CONTROLBAR_LABEL_TRADE";
    final String SETTINGS_TITLE = "SETTINGS_TITLE";
    final String SETTINGS_SEARCHING_MUSIC_LIBRARY = "SETTINGS_SEARCHING_MUSIC_LIBRARY";
    final String SETTINGS_SEARCHING_ENTIRE_SYSTEM = "SETTINGS_SEARCHING_ENTIRE_SYSTEM";
    final String SETTINGS_PARSING_MUSIC_LIBRARY = "SETTINGS_PARSING_MUSIC_LIBRARY";
    final String SETTINGS_SELECT_MUSIC_LIBRARY = "SETTINGS_SELECT_MUSIC_LIBRARY";
    final String SETTINGS_LIBRARYCHOOSER_DESCRIPTION = "SETTINGS_LIBRARYCHOOSER_DESCRIPTION";
    final String SETTINGS_LIBRARYCHOOSER_TITLE = "SETTINGS_LIBRARYCHOOSER_TITLE";
    final String SETTINGS_LIBRARYCHOOSER_TOOLTIP = "SETTINGS_LIBRARYCHOOSER_TOOLTIP";
    final String SETTINGS_LIBRARYCHOOSER_MNEMONIC = "SETTINGS_LIBRARYCHOOSER_MNEMONIC";
    final String SETTINGS_LIBRARYCHOOSER_APPROVE = "SETTINGS_LIBRARYCHOOSER_APPROVE";
    final String ABOUT_TITLE = "ABOUT_TITLE";
    final String GETSTARTED_TITLE = "GETSTARTED_TITLE";
    final String GETSTARTED_DISPLAY_STARTUP = "GETSTARTED_DISPLAY_STARTUP";
    final String GETSTARTED_DISPLAY_NEVER = "GETSTARTED_DISPLAY_NEVER";
    final String PREFERENCES_TITLE = "PREFERENCES_TITLE";

    // Tooltips
    final String TOOLTIP_BUTTON_TRADE = "TOOLTIP_BUTTON_TRADE";
    final String TOOLTIP_BUTTON_SEARCH = "TOOLTIP_BUTTON_SEARCH";

    // Messages
    final String ERROR_PARSING_MUSIC_LIBRARY = "ERROR_PARSING_MUSIC_LIBRARY";
    final String ERROR_NO_MUSIC_LIBRARY = "ERROR_NO_MUSIC_LIBRARY";
    final String ERROR_NO_ITUNES_MUSIC_LIBRARY = "ERROR_NO_ITUNES_MUSIC_LIBRARY";
    final String ERROR_NO_MUSIC_LIBRARY_SELECTED = "ERROR_NO_MUSIC_LIBRARY_SELECTED";
    final String ERROR_BEAN_NOT_FOUND = "ERROR_BEAN_NOT_FOUND";
    final String ERROR_INCORRECT_TRACK_NUMBER = "ERROR_INCORRECT_TRACK_NUMBER";
    final String ERROR_INCORRECT_DISC_NUMBER = "ERROR_INCORRECT_DISC_NUMBER";
    final String ERROR_INCORRECT_TRACK_COUNT = "ERROR_INCORRECT_TRACK_COUNT";
}