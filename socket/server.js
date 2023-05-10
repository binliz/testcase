require('dotenv').config();
const { createServer } = require("http");
const { Server } = require("socket.io");

const httpServer = createServer(handler);

const io = new Server(httpServer, {
    cors: { origin: "*" },
});

const Redis = require('ioredis');
const redis = new Redis({
    host: process.env.REDIS_HOST,
    port: process.env.REDIS_PORT,
    password: process.env.REDIS_PASSWORD
});

httpServer.listen(6001, function() {
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
