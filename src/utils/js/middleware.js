function ajaxErrorHandler(jqXHR, textStatus, errorThrown, error) {
    console.log("Detailed error information:");
    console.log("Stringy Response:", JSON.stringify(jqXHR));
    console.log("Server Response: ", jqXHR);
    console.log("Status of the request: ", textStatus);
    console.log("Error thrown by the request: ", errorThrown);
    console.log("Error:", error);
    return false;
}
