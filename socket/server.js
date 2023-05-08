var app = require('http').createServer(handler);
var io = require('socket.io')(app,{
    cors: { origin: "*" },
});

var Redis = require('ioredis');
var redis = new Redis({
    host: 'redis',
    port: 6379,
    password: 'rPzm1Q6NqFqejQRhaJ/yCPesmdSdkOhZArnftBfRpSOYIbVA2Rq+M/xDOcM31D9z05KpWIoUeQKZSVxc'
});

app.listen(6001, function() {
    console.log('Server is running!');
});

function handler(req, res) {
    res.writeHead(200);
    res.end('');
}

io.on('connection', function(socket) {
    //
});

redis.psubscribe('*', function(err, count) {
    //
});

redis.on('pmessage', function(subscribed, channel, message) {
    message = JSON.parse(message);
    io.emit(channel+":"+message.event, message.data);
});
