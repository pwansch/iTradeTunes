package com.itradetunes;

import java.io.IOException;
import java.net.URI;

import org.apache.http.client.ClientProtocolException;
import org.apache.http.client.ResponseHandler;
import org.apache.http.client.HttpClient;
import org.apache.http.client.methods.HttpGet;
import org.apache.http.impl.client.BasicResponseHandler;
import org.apache.http.impl.client.DefaultHttpClient;

public class Service {

    // Instance variables
    private HttpClient httpClient;

    public Service() {
	httpClient = new DefaultHttpClient();
    }

    protected String httpGet(URI uri) {
	try {
	    HttpGet httpGet = new HttpGet(uri);

	    // Create a response handler
	    ResponseHandler<String> responseHandler = new BasicResponseHandler();
	    String responseBody = httpClient.execute(httpGet, responseHandler);
	    return responseBody;

	} catch (ClientProtocolException cpe) {
	    // Ignore, but log
	    Utils.logSevere(cpe);
	} catch (IOException ioe) {
	    // Ignore, but log
	    Utils.logSevere(ioe);
	}

	return null;
    }

    protected void shutdown() {
	// When HttpClient instance is no longer needed,
	// shut down the connection manager to ensure
	// immediate deallocation of all system resources
	httpClient.getConnectionManager().shutdown();
    }
}