package com.itradetunes;

import java.util.ArrayList;
import java.util.Comparator;
import java.util.List;
import java.math.BigDecimal;

import javax.swing.table.TableCellRenderer;

public class AlbumBean implements Constants, Cloneable {
    // Class variables
    private final static String PROP_TRADE = "trade";
    private final static String PROP_PRICE = "price";
    private final static String PROP_ALBUM = "album";
    private final static String PROP_TRACKS = "tracks";
    private final static String PROP_ARTIST = "artist";
    private final static String PROP_GENRE = "genre";
    private final static String PROP_DISC_COUNT = "disc_Count";
    public final static String NAME_TRADE = "Trade";
    public final static String NAME_PRICE = "Price";
    public final static String NAME_ALBUM = "Album";
    public final static String NAME_TRACKS = "Tracks";
    public final static String NAME_ARTIST = "Artist";
    public final static String NAME_GENRE = "Genre";
    public final static String NAME_DISC_COUNT = "Disc Count";
    private final static String[] visibleProps = { PROP_TRADE, PROP_PRICE, PROP_ALBUM, PROP_TRACKS, PROP_ARTIST, PROP_GENRE, PROP_DISC_COUNT };
    private final static int[] width = { 5, 10, 30, 5, 30, 10, 5, 5 };
    private final static PriceRenderer priceRenderer = new PriceRenderer();

    // Properties for an album in the music library
    private int hashCode;
    private Boolean trade;
    private BigDecimal price;
    private String album;
    private Integer tracks;
    private String artist;
    private String genre;
    private Integer disc_Count;
    private int[] trackCounts;
    private SongBean[][] trackBeans;
    boolean consistent;

    public static String[] getVisibleProperties() {
	return visibleProps;
    }

    public static TableCellRenderer getTableCellRenderer(String property) {
	// Get property specific renderer for bean
	if (PROP_PRICE.equals(property))
	{
	    return priceRenderer;
	}
	else
	{
	return null;
	}
    }

    public static SortedHeaderRenderer getColumnHeaderRenderer(TableModel tableModel) {
	// We make all columns sortable
	return new SortedHeaderRenderer(tableModel);
    }

    public static int getRelativeColumnWidth(int index) {
	return width[index];
    }

    public static String getColumnName(int index) {
	return Utils.getResourceString(ALBUMBEAN_PREFIX + visibleProps[index]);
    }

    public static Comparator<AlbumBean> getBeanSorter(String columnName, boolean ascending) {
	return new AlbumBeanSorter(columnName, ascending);
    }

    public AlbumBean() {
	// Initialize mandatory properties
	hashCode = -1;
	trade = new Boolean(false);
	price = new BigDecimal(0.0);
	album = "";
	tracks = new Integer(0);
	disc_Count = new Integer(0);

	// Initialize optional properties
	artist = null;
	genre = null;
	trackCounts = new int[MAXIMUM_NUMBER_OF_DISCS];
	trackBeans = new SongBean[MAXIMUM_NUMBER_OF_DISCS][MAXIMUM_NUMBER_OF_TRACKS];
	consistent = true;
    }

    public void setHashCode() {
	String hashString = album.toUpperCase();

	for (int i = 0; i < MAXIMUM_NUMBER_OF_DISCS; i++) {
	    for (int j = 0; j < MAXIMUM_NUMBER_OF_TRACKS; j++) {
		if (trackBeans[i][j] != null) {
		    hashString += '\uFFFF' + (trackBeans[i][j].getName() == null ? "" : trackBeans[i][j].getName().toUpperCase()) + '\uFFFF'
			    + (trackBeans[i][j].getArtist() == null ? "" : trackBeans[i][j].getArtist().toUpperCase());
		}
	    }

	}
	// System.out.println(hashString);
	hashCode = album.hashCode();
    }

    public SongBean[] getSongs() {
	List<SongBean> songList = new ArrayList<SongBean>();

	for (int i = 0; i < MAXIMUM_NUMBER_OF_DISCS; i++) {
	    for (int j = 0; j < MAXIMUM_NUMBER_OF_TRACKS; j++) {
		if (trackBeans[i][j] != null) {
		    songList.add(trackBeans[i][j]);
		}
	    }

	}

	return songList.toArray(new SongBean[songList.size()]);
    }

    public boolean checkConsistency() {
	if (!consistent) {
	    return false;
	}

	int discIndex = 0;
	int trackIndex = 0;
	int totalTracks = 0;
	int totalTrackCount = 0;
	boolean validTrackCount = true;

	// Check track counts
	while ((trackCounts[discIndex] == -1 || trackCounts[discIndex] > 0) && discIndex < MAXIMUM_NUMBER_OF_DISCS) {
	    if (trackCounts[discIndex] == -1) {
		validTrackCount = false;
	    }

	    if (validTrackCount) {
		totalTrackCount += trackCounts[discIndex];
	    }

	    while (trackBeans[discIndex][trackIndex] != null && trackIndex < MAXIMUM_NUMBER_OF_TRACKS) {
		totalTracks++;
		trackIndex++;
	    }

	    discIndex++;
	}

	// Check total tracks and do not allow albums with a single track
	if (totalTracks != tracks.intValue() || totalTracks <= MINIMUM_NUMBER_OF_TRACKS) {
	    consistent = false;
	}

	// Check if the sum of the track counts if not -1 equals the total
	// tracks
	if (validTrackCount && totalTrackCount != totalTracks) {
	    consistent = false;
	}

	return consistent;
    }

    public void addSong(SongBean song) throws LibraryException {
	// Check track count
	if (song.getTrack_Count() > MAXIMUM_NUMBER_OF_TRACKS) {
	    consistent = false;
	    throw new LibraryException(Utils.getResourceString(ERROR_INCORRECT_TRACK_COUNT));
	}

	// Check track number
	if (song.getTrack_Number() < 1 || song.getTrack_Number() > MAXIMUM_NUMBER_OF_TRACKS) {
	    consistent = false;
	    throw new LibraryException(Utils.getResourceString(ERROR_INCORRECT_TRACK_NUMBER));
	}

	// Check disc number
	if (song.getDisc_Number() >= MAXIMUM_NUMBER_OF_DISCS) {
	    consistent = false;
	    throw new LibraryException(Utils.getResourceString(ERROR_INCORRECT_DISC_NUMBER));
	} else {
	    // Ignore negative disc numbers and set to 1
	    song.setDisc_Number("1");
	}

	// Calculate disc and track index
	int discIndex = song.getDisc_Number() - 1;
	int trackIndex = song.getTrack_Number() - 1;

	// Add track count for disc and track id
	trackCounts[discIndex] = song.getTrack_Count();
	trackBeans[discIndex][trackIndex] = song;
    }

    public int compare(String columnName, Object o1, Object o2) {
	// Check for nulls, the super compare takes care of that
	if (o1 == null) {
	    return 1;
	}

	if (o2 == null) {
	    return -1;
	}

	if (PROP_TRACKS.equalsIgnoreCase(columnName) || PROP_DISC_COUNT.equalsIgnoreCase(columnName)) {
	    int i1 = ((Integer) o1).intValue();
	    int i2 = ((Integer) o2).intValue();
	    if (i1 < i2) {
		return -1;
	    }

	    return (i1 > i2) ? 1 : 0;
	} else {
	    // Treat empty strings as null strings
	    if (o1 != null && (o1.toString()).trim().length() == 0) {
		o1 = null;
	    }
	    if (o2 != null && (o2.toString()).trim().length() == 0) {
		o2 = null;
	    }

	    if (o1 == null && o2 == null) {
		return 0;

	    } else if (o1 == null) {
		return 1;
	    } else if (o2 == null) {
		return -1;
	    } else {
		// Compare as strings
		return (o1.toString()).compareToIgnoreCase(o2.toString());
	    }
	}
    }

    public boolean matches(String str) {
	if (str == null) {
	    return true;
	}

	// Compare upper case
	String strUpper = str.toUpperCase();
	return (album != null && (album.toUpperCase().indexOf(strUpper) != -1) || artist != null && (artist.toUpperCase().indexOf(strUpper) != -1) || genre != null
		&& (genre.toUpperCase().indexOf(strUpper) != -1)) ? true : false;
    }

    public Boolean getTrade() {
	return trade;
    }

    public void setTrade(boolean trade) {
	this.trade = new Boolean(trade);
    }

    public BigDecimal getPrice() {
	return price;
    }

    public void setPrice(BigDecimal price) {
	this.price = price;
    }

    public String getAlbum() {
	return album;
    }

    public void setAlbum(String album) {
	this.album = album;
    }

    public Integer getTracks() {
	return tracks;
    }

    public void incTracks() {
	this.tracks = new Integer(this.tracks.intValue() + 1);
    }

    public String getArtist() {
	return artist;
    }

    public void setArtist(String artist) {
	this.artist = artist;
    }

    public String getGenre() {
	return genre;
    }

    public void setGenre(String genre) {
	this.genre = genre;
    }

    public int getDisc_Count() {
	return disc_Count;
    }

    public void setDisc_Count(int disc_Count) {
	this.disc_Count = disc_Count;
    }

    public Boolean getConsistent() {
	return consistent;
    }

    public void setConsistent(boolean consistent) {
	this.consistent = consistent;
    }

    @SuppressWarnings("rawtypes")
    public static Class getColumnClass(int index) {
	switch (index) {
	case 0:
	    return Boolean.class;
	case 1:
	    return BigDecimal.class;
	case 3:
	case 6:
	    return Integer.class;
	default:
	    return String.class;
	}
    }
}