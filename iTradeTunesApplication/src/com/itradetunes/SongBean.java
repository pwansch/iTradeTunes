package com.itradetunes;

public class SongBean {
    public final static String NAME_TRACK_ID = "Track ID";

    // Properties for a song in the music library
    private int track_ID;
    private String name;
    private String artist;
    private String album;
    private String genre;
    private String kind;
    private int disc_Number;
    private int disc_Count;
    private int track_Number;
    private int track_Count;
    private boolean compilation;
    private String track_Type;

    public SongBean() {
	// Initialize properties
	track_ID = -1;
	name = "";

	// Optional properties
	artist = null;
	album = null;
	genre = null;
	kind = null;
	disc_Number = -1;
	disc_Count = -1;
	track_Number = -1;
	track_Count = -1;
	compilation = false;
	track_Type = null;
    }

    public int getTrack_ID() {
	return track_ID;
    }

    public void setTrack_ID(String track_ID) {
	try {
	    this.track_ID = Integer.valueOf(track_ID);
	} catch (NumberFormatException nfe) {
	    this.track_ID = -1;
	}
    }

    public String getName() {
	return name;
    }

    public void setName(String name) {
	this.name = name;
    }

    public String getArtist() {
	return artist;
    }

    public void setArtist(String artist) {
	this.artist = artist;
    }

    public String getAlbum() {
	return album;
    }

    public void setAlbum(String album) {
	this.album = album;
    }

    public String getGenre() {
	return genre;
    }

    public void setGenre(String genre) {
	this.genre = genre;
    }

    public String getKind() {
	return kind;
    }

    public void setKind(String kind) {
	this.kind = kind;
    }

    public int getDisc_Number() {
	return disc_Number;
    }

    public void setDisc_Number(String disc_Number) {
	try {
	    this.disc_Number = Integer.valueOf(disc_Number);
	} catch (NumberFormatException nfe) {
	    this.disc_Number = -1;
	}
    }

    public int getDisc_Count() {
	return disc_Count;
    }

    public void setDisc_Count(String disc_Count) {
	try {
	    this.disc_Count = Integer.valueOf(disc_Count);
	} catch (NumberFormatException nfe) {

	    this.disc_Count = -1;
	}
    }

    public int getTrack_Number() {
	return track_Number;
    }

    public void setTrack_Number(String track_Number) {
	try {
	    this.track_Number = Integer.valueOf(track_Number);
	} catch (NumberFormatException nfe) {

	    this.track_Number = -1;
	}
    }

    public boolean getCompilation() {
	return compilation;
    }

    public void setCompilation(String compilation) {
	this.compilation = Boolean.valueOf(compilation);
    }

    public int getTrack_Count() {
	return track_Count;
    }

    public void setTrack_Count(String track_Count) {
	try {
	    this.track_Count = Integer.valueOf(track_Count);
	} catch (NumberFormatException nfe) {

	    this.track_Count = -1;
	}
    }

    public String getTrack_Type() {
	return track_Type;
    }

    public void setTrack_Type(String track_Type) {
	this.track_Type = track_Type;
    }
}