#!/usr/bin/env node

var app = require('http').createServer(handler)
var io = require('socket.io')(app);
var redis = require("redis");
var redisClient = redis.createClient();
var sub = redis.createClient();
var pub = redis.createClient();

app.listen(9999);

sub.subscribe('new-message');
//redisClient.publish('message-to-gliph', "Shepherd: testing");
sub.on('message', function (channel, chatId) {
    console.log('New message: ' + chatId);

    io.to(chatId).emit('new-message');

    //messages = redisClient.zrangebyscore([message, '-inf', '+inf'], function(err, resp) {
    //    console.log(message);
    //    console.log(err);
    //    console.log(resp);
    //    socket.emit('news', { channel: resp });
    //});
});

function handler (req, res) {
    res.writeHead(200);
    res.end('Hello. I am the server that makes the messages show up fast. :)');
}

io.on('connection', function (socket) {

    socket.on('join-room', function(room) {
        socket.join(room);
        socket.room = room;
    });

    socket.on('media-upload-progress', function(data) {
        console.log('Media upload progress: ' + data.percent + '% ' + data.mediaId);
        io.to(socket.room).emit('media-upload-progress', data);
    });

    socket.on('message-upload-complete', function(messageId) {
        pub.publish('message-to-gliph-'+socket.room, 'message:'+messageId);
    });
    socket.on('media-upload-complete', function(mediaId) {
        console.log('Media uploaded: ' + mediaId);
        io.to(socket.room).emit('media-upload-complete', mediaId);
    });

});
