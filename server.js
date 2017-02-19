var app = require('express')();
var path = require('path');
var http = require('http').Server(app);
var io = require('socket.io')(http, {origins:'language-exchange-cafe.herokuapp.com:* http://language-exchange-cafe.herokuapp.com:*'});
var connection = require('./config.js').connection;

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

http.listen(3000, function(){
  console.log('listening on *:3000');
});
