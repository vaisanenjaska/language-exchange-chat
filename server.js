var app = require('express')();
var path = require('path');
var http = require('http').Server(app);
var socketIO = require('socket.io')(http);
var connection = require('./config.js').connection;
var PORT = 3000;

var server = app()
  .listen(PORT, () => console.log(`Listening on ${ PORT }`));

var io = socketIO(server);

app.get('/', function(req, res){
  res.sendFile(path.join(__dirname, 'index.html'));
});

io.on('connection', function(socket){
  console.log('a user connected');
  socket.on('disconnect', function(){
    console.log('user disconnected');
  });
  socket.on('chat message', function(msg){
    var m = JSON.parse(msg);
    var message = m['message'];
    var name = m['name'];
    var target = m['target'];

    connection.connect;
    var idQuery = connection.query("Select user_id from User where name = '" + name + "'");
    idQuery.on('result', function(row) {
      var user = row.user_id;
      var queryString = "INSERT INTO Message(user_id,text,target_user) VALUES('" + user + "','" + message + "','" + target + "')";
      connection.query(queryString, function(err) {
          if (err) throw err;
      });
    })
    var msgArr = {
      name: name,
      message: message
    }
    io.emit('chat message', JSON.stringify(msgArr));
  });
});
