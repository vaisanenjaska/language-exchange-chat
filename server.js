var app = require('express')();
var path = require('path');
var http = require('http').Server(app);
var io = require('socket.io')(http);
var connection = require('./config.js').connection;

app.get('/', function(req, res){
  res.sendFile(window.location + '/index.html');
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
    if (m['target'] != undefined) {
      var target = m['target'];
    } else if (m['group'] != undefined) {
      var group = m['group'];
    }
    var targetName;

    connection.connect;
    if (target != undefined) {
      var Query = connection.query("Select name from User where user_id = '" + target + "'");
      Query.on('result', function(row, err) {
        if (err) throw err;
        targetName = row.name;
        var msgArr = {
          name: name,
          message: message,
          target: targetName
        }
        io.emit('chat message', JSON.stringify(msgArr));
        var idQuery = connection.query("Select user_id from User where name = '" + name + "'");
        idQuery.on('result', function(row, err) {
          if (err) throw err;
          var user = row.user_id;

          if(user && targetName) {
            var queryString = "INSERT INTO Message(user_id,text,target_user) VALUES('" + user + "','" + message + "','" + target + "')";
            connection.query(queryString, function(err) {
                if (err) throw err;
            });
          }
        })
      })
    } else if (group != undefined) {
      var msgArr = {
        name: name,
        message: message,
        group: group
      }
      io.emit('chat message', JSON.stringify(msgArr));
      var idQuery = connection.query("Select user_id from User where name = '" + name + "'");
      idQuery.on('result', function(row, err) {
        if (err) throw err;
        var user = row.user_id;

        if(user) {
          var queryString = "INSERT INTO Message(user_id,text,group_id) VALUES('" + user + "','" + message + "','" + group + "')";
          connection.query(queryString, function(err) {
              if (err) throw err;
          });
        }
      })
    }
  });
});

http.listen(process.env.PORT || 3000, function(){
  console.log('listening on *:3000');
});
