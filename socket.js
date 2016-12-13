/**
 * Created by Administrator on 2016/11/30 0030.
 */
var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();
redis.subscribe('test-channel.21', function(err, count) {
});
redis.on('message', function(channel, message) {

    console.log('Message Recieved: ' + message);
    message = JSON.parse(message);
    io.emit(channel + ':' + message.event, message.data.data);
});
http.listen(3000, function(){
    console.log('Listening on Port 3000');
});