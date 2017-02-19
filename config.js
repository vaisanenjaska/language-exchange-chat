var mysql = require('mysql');

exports.connection = mysql.createConnection(
    {
      host     : process.env.MYSQL_DB.toString(),
      user     : process.env.MYSQL_HOST.toString(),
      password : process.env.MYSQL_PASS.toString(),
      database : process.env.MYSQL_USER.toString(),
    }
);
