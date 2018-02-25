var express = require('express');
var app = express();
var server = require('http').Server(app);
var io = require('socket.io')(server);

server.listen(3000);

io.on('connection', function (socket) {
    // listen when an update event is triggered from php
    socket.on('update-device', function(message) {
        console.log(message);

        // send it for wall the front (js) listeners
        io.emit('update-device', message);
    });

    socket.on('create-device', function(message) {
        console.log(message);

        io.emit('create-device', message);
    });

    socket.on('delete-device', function(message) {
        console.log(message);

        io.emit('delete-device', message);
    });
});
