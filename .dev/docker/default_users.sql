CREATE USER 'mysql_admin'@'%' IDENTIFIED BY 'mysql_admin_password';
GRANT ALL PRIVILEGES ON *.* TO 'mysql_admin'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
