<script>
    var app = require('express')();
    var http = require('http').Server(app);
    var io = require('socket.io')(http);
    var Redis = require('ioredis');
    var redis = new Redis();
    redis.subscribe('test-channel.{{ $user->id }}', function(err, count) {
        console.log(err,count);
    });
    redis.on('message', function(channel, message) {
        message = JSON.parse(message);
        console.log('Message Recieved: ' + message.data.data);
        io.emit(channel + ':' + message.event, message.data.data);
    });
    http.listen(3000, function(){
        console.log('Listening on Port 3000');
    });
</script>