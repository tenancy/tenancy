CREATE USER 'mysql_admin'@'%' IDENTIFIED BY 'mysql_admin_password';
GRANT ALL PRIVILEGES ON * . * TO 'mysql_admin'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;

CREATE DATABASE mysql_database;
CREATE USER 'mysql_user'@'%' IDENTIFIED BY 'mysql_user_password';
GRANT ALL PRIVILEGES ON rbs_database . * TO 'mysql_user'@'%';
FLUSH PRIVILEGES;