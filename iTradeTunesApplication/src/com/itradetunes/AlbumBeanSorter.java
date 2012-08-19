package com.itradetunes;

import java.util.*;
import java.beans.*;

public class AlbumBeanSorter implements Comparator<AlbumBean>, Constants {
    private String columnName;
    private boolean ascending;

    AlbumBeanSorter(String columnName, boolean ascending) {
	this.columnName = columnName;
	this.ascending = ascending;
    }

    public int compare(AlbumBean a, AlbumBean b) {
	AlbumBean beanA = a;
	AlbumBean beanB = b;
	Object valueA = null;
	Object valueB = null;
	Expression exprA = new Expression(beanA, "get" + columnName, new Object[0]);
	Expression exprB = new Expression(beanB, "get" + columnName, new Object[0]);
	try {
	    exprA.execute();
	    valueA = exprA.getValue();
	    exprB.execute();
	    valueB = exprB.getValue();

	} catch (Exception e) {
	    // Ignore and log, cannot occur
	    Utils.logSevere(e);
	}

	// Compare strings
	int result = beanA.compare(columnName, valueA, valueB);

	return (ascending) ? result : -result;
    }
}