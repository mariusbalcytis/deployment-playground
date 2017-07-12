docker exec -it env_mysql_1 bash -c 'mysql -ppass app < /docker-entrypoint-initdb.d/init.sql'
