/*创建用户及对应数据库，用户对对应的数据库享有所有权限*/
CREATE USER 'oneday'@'localhost' IDENTIFIED BY "oneday";
GRANT USAGE ON * . * TO 'oneday'@'localhost' IDENTIFIED BY "oneday" WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0 ;
CREATE DATABASE IF NOT EXISTS `oneday` ;
GRANT ALL PRIVILEGES ON `oneday` . * TO 'oneday'@'localhost';

/*使用数据库*/
use oneday;

/*创建表*/
create table user(
	uid bigint NOT NULL AUTO_INCREMENT,
	name varchar(100) NOT NULL,/*TODO：唯一*/
	pwd varchar(100) NOT NULL,

	primary key(uid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
insert user values(NULL,"wbx","wbx");

create table schedule(
	sid bigint NOT NULL AUTO_INCREMENT,
	title TEXT NOT NULL,
	tag TEXT NOT NULL,
	location TEXT not null,
	startTime datetime NOT NULL,
	endTIme datetime NOT NULL,
	content text not null,
	`check` text not null,
	participant text not null,

	primary key(sid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;