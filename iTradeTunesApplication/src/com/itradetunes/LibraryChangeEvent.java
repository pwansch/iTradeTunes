package com.itradetunes;

import java.util.*;

public class LibraryChangeEvent extends EventObject {
    // Types
    public static final int INSERT = 0;
    public static final int UPDATE = 1;

    // Instance variables
    private AlbumBean[] entries;
    private int type;

    public LibraryChangeEvent(Library source, AlbumBean[] entries, int type) {
	// Initialize
	super(source);
	this.entries = entries;
	this.type = type;
    }

    public AlbumBean[] getEntries() {
	return entries;
    }

    public int getType() {
	return type;
    }
}