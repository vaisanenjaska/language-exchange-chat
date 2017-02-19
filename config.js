var mysql = require('mysql');

exports.connection = mysql.createConnection(
    {
      host     : 'process.env.MYSQL_DB',
      user     : 'process.env.MYSQL_HOST',
      password : 'process.env.MYSQL_PASS',
      database : 'process.env.MYSQL_USER',
    }
);
