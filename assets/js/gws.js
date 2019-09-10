var webSocket = WS.connect("ws://127.0.0.1:8080");

webSocket.on("socket/connect", function (session) {
    //session is an AutobahnJS WAMP session.

    console.log("Successfully Connected!");
});

webSocket.on("socket/disconnect", function (error) {
    //error provides us with some insight into the disconnection: error.reason and error.code

    console.log("Disconnected for " + error.reason + " with code " + error.code);
});