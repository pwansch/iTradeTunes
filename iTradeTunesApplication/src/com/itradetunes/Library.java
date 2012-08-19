package com.itradetunes;

import java.beans.Statement;
import java.io.*;
import java.util.*;

import javax.swing.event.EventListenerList;
import javax.xml.parsers.*;
import javax.xml.xpath.*;

import org.w3c.dom.Document;
import org.w3c.dom.Element;
import org.w3c.dom.Node;
import org.w3c.dom.NodeList;
import org.xml.sax.*;

public class Library implements Constants {
    // Constants
    private static final String XPATH_LIBRARY_LIST = "/plist/dict/key[.='Tracks']/following-sibling::dict[position()=1]/dict/*";

    // Instance variables
    private EventListenerList listenerList;
    private boolean changed;
    protected List<AlbumBean> libraryList;
    protected List<String> warningList;

    private File musicLibrary; // LocalITunesMusicLibrary

    public Library(File musicLibrary) throws LibraryException {
	// Initialize
	libraryList = new ArrayList<AlbumBean>();
	warningList = new ArrayList<String>();
	listenerList = new EventListenerList();
	changed = false;
	this.musicLibrary = musicLibrary;

	// Parse the input file
	parse(this.musicLibrary);
    }

    public AlbumBean[] getResult() {
	synchronized (libraryList) {
	    AlbumBean[] res;
	    res = libraryList.toArray(new AlbumBean[libraryList.size()]);
	    return res;
	}
    }

    // This methods allows classes to register
    // TODO: Set up listener
    public void addLibraryListener(LibraryListener listener) {
	listenerList.add(LibraryListener.class, listener);
    }

    // This methods allows classes to unregister
    public void removeLibraryListener(LibraryListener listener) {
	listenerList.remove(LibraryListener.class, listener);
    }

    protected void fireLibraryChangeEvent(LibraryChangeEvent evt) {
	Object[] listeners = listenerList.getListenerList();

	// Each listener occupies two elements
	// The first is the listener class and the second is the listener
	// instance
	for (int i = 0; i < listeners.length; i += 2) {
	    if (listeners[i].equals(LibraryListener.class)) {
		((LibraryListener) listeners[i + 1]).libraryChanged(evt);
	    }
	}
    }

    public void updateBean(AlbumBean newBean, AlbumBean oldBean) throws LibraryException {
	synchronized (libraryList) {
	    // Find the bean
	    int index = libraryList.indexOf(oldBean);
	    if (index == -1) {
		LibraryException le = new LibraryException(Utils.getResourceString(ERROR_BEAN_NOT_FOUND));
		throw le;
	    }

	    // Set the new bean
	    libraryList.set(index, newBean);
	    setChanged();
	    fireLibraryChangeEvent(new LibraryChangeEvent(this, new AlbumBean[] { oldBean }, LibraryChangeEvent.UPDATE));
	}
    }

    public boolean hasChanged() {
	return changed;
    }

    public void setChanged() {
	changed = true;
    }

    public int getCount() {
	synchronized (libraryList) {
	    return libraryList.size();
	}
    }

    public Document parse(File file) throws LibraryException {
	try {
	    FileInputStream input = new FileInputStream(file);

	    // Parse the input file
	    return parse(input);
	} catch (IOException ioe) {
	    throw new LibraryException(ioe);
	}
    }

    private AlbumBean addOrUpdateAlbum(Map<Integer, AlbumBean> map, SongBean song, boolean albumOnly) {
	// Add an album bean
	if (song != null && song.getAlbum() != null) {
	    int hashCode = (song.getAlbum() + (albumOnly ? "" : '\uFFFF' + song.getArtist())).hashCode();

	    // Check if the album beam already exists
	    AlbumBean album = map.get(hashCode);
	    if (album == null) {
		album = new AlbumBean();
		album.setAlbum(song.getAlbum());
		map.put(hashCode, album);
	    }

	    // Update the album properties
	    try {
		// Add the track id to the album bean
		album.addSong(song);
		album.incTracks();
		album.setArtist(song.getArtist());
		album.setGenre(song.getGenre());
		album.setDisc_Count(song.getDisc_Count());
	    } catch (LibraryException le) {
		// There was an error adding the song to this album, remove it
		map.remove(hashCode);

		// Add to warning message
		warningList.add(song.getName() + " " + le);
	    }

	    return album;
	}

	return null;
    }

    protected Document parse(InputStream inputStream) throws LibraryException {
	Map<Integer, SongBean> songMap = new HashMap<Integer, SongBean>();
	Map<Integer, AlbumBean> albumMap = new HashMap<Integer, AlbumBean>();
	Map<Integer, AlbumBean> secondaryAlbumMap = new HashMap<Integer, AlbumBean>();
	warningList.clear();

	try {
	    // Create a builder factory
	    DocumentBuilderFactory factory = DocumentBuilderFactory.newInstance();
	    factory.setValidating(false);
	    factory.setNamespaceAware(true);
	    factory.setIgnoringElementContentWhitespace(true);
	    factory.setIgnoringComments(true);

	    // Create the builder and parse the file
	    DocumentBuilder builder = factory.newDocumentBuilder();

	    // Set an error listener and parse the document
	    builder.setErrorHandler(new iTradeTunesLibraryErrorHandler());
	    builder.setEntityResolver(new iTradeTunesLibraryResolver());
	    Document document = builder.parse(inputStream);

	    synchronized (libraryList) {
		// Query the library document and build the library list
		XPath xPath = XPathFactory.newInstance().newXPath();
		XPathExpression xPathExpression = xPath.compile(XPATH_LIBRARY_LIST);
		NodeList nodelist = (NodeList) xPathExpression.evaluate(document, XPathConstants.NODESET);

		// Process the elements in the nodelist
		SongBean song = null;

		for (int i = 0; i < nodelist.getLength(); i++) {
		    boolean isTrackID = false;

		    // Get element and child nodes
		    Element elem = (Element) nodelist.item(i);
		    NodeList list = elem.getChildNodes();

		    // Get node value
		    Node childKey = list.item(0);
		    String key = childKey.getNodeValue();

		    // Check if we have to create a new bean
		    if (SongBean.NAME_TRACK_ID.equals(key)) {
			isTrackID = true;
			SongBean previousSong = song;
			song = new SongBean();
			if (previousSong != null && !("AAC audio file".equals(previousSong.getKind()) || "MPEG audio file".equals(previousSong.getKind()))) {
			    songMap.remove(previousSong.getTrack_ID());
			} else {
			    // Add an album bean
			    addOrUpdateAlbum(albumMap, previousSong, false);
			}
		    }

		    // The first parameter is the key
		    String prop = childKey.getNodeValue().replace(' ', '_');

		    // The second parameter is the value
		    i++;

		    // Get element and child nodes
		    elem = (Element) nodelist.item(i);

		    // Check for boolean properties
		    Object value = null;
		    // Get node value
		    list = elem.getChildNodes();
		    childKey = list.item(0);
		    value = (childKey == null) ? elem.getNodeName() : childKey.getNodeValue();

		    if (isTrackID) {
			isTrackID = false;
		    }

		    // Set the property of the song bean
		    Statement stmt = new Statement(song, "set" + prop, new Object[] { value });
		    try {
			stmt.execute();

		    } catch (Exception e) {
			// Ignore that field, we do not have it in our bean
		    }

		    // If the property is the track ID, add the song to the hash
		    // map
		    if (SongBean.NAME_TRACK_ID.equals(key)) {
			int trackID = Integer.valueOf((String) value);
			songMap.put(trackID, song);
		    }
		}

		// Update album for last song
		addOrUpdateAlbum(albumMap, song, false);

		// Check the album map for inconsistencies
		Iterator<AlbumBean> albums = albumMap.values().iterator();
		while (albums.hasNext()) {
		    AlbumBean album = albums.next();
		    if (album.checkConsistency()) {
			libraryList.add(album);
			album.setHashCode();
		    } else {
			// Add an inconsistent album only using the album title
			SongBean[] songs = album.getSongs();
			for (int i = 0; i < songs.length; i++) {
			    addOrUpdateAlbum(secondaryAlbumMap, songs[i], true);
			}
		    }
		}

		// Check secondary album map for consistency
		albums = secondaryAlbumMap.values().iterator();
		while (albums.hasNext()) {
		    AlbumBean album = albums.next();
		    if (album.checkConsistency()) {
			libraryList.add(album);
			album.setHashCode();
		    } else {
			// This album cannot be matched
			// TODO: Add to warning message
		    }
		}

		setChanged();
	    }

	    return document;
	} catch (IOException ioe) {
	    // Log an expected connect exception
	    throw new LibraryException(ioe);
	} catch (SAXException se) {
	    // Catch all other exceptions
	    throw new LibraryException(se);
	} catch (ParserConfigurationException pce) {
	    // Catch all other exceptions
	    Utils.logSevere(pce);
	    throw new LibraryException(pce);
	} catch (XPathExpressionException xpe) {
	    // Catch all other exceptions
	    Utils.logSevere(xpe);
	    throw new LibraryException(xpe);
	} catch (NumberFormatException nfe) {
	    // Catch all other exceptions
	    throw new LibraryException(nfe);
	}
    }

    // Inner class for SAX error handler
    class iTradeTunesLibraryErrorHandler implements ErrorHandler {
	// Ignore fatal errors, the parser will not continue processing
	public void fatalError(SAXParseException fatal) throws SAXException {
	}

	// Treat validation errors as fatal
	public void error(SAXParseException error) throws SAXParseException {
	    throw error;
	}

	// Treat warnings as fatal too
	public void warning(SAXParseException warning) throws SAXParseException {
	    throw warning;
	}
    }

    // Inner class for entity resolver
    public class iTradeTunesLibraryResolver implements EntityResolver {
	// This method is called whenever an external entity is accessed
	// for the first time.
	public InputSource resolveEntity(String publicId, String systemId) {
	    // Map the external entity to a local file
	    if (URL_PROPERTY_LIST.equals(systemId)) {
		return new InputSource(ClassLoader.getSystemResourceAsStream(FILENAME_PROPERTY_LIST));
	    }

	    // Returning null causes the caller to try accessing the systemid
	    return null;
	}
    }
}