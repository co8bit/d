CREATE USER 'onedayt54w'@'localhost' IDENTIFIED BY 'onedaysfkl76jk';
GRANT USAGE ON *.* TO 'onedayt54w'@'localhost' IDENTIFIED BY 'onedaysfkl76jk' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
CREATE DATABASE IF NOT EXISTS `oneday`;
GRANT ALL PRIVILEGES ON `oneday`.* TO 'onedayt54w'@'localhost';

/*使用数据库*/
use oneday;

/*创建表*/
create table user(
	uid bigint NOT NULL AUTO_INCREMENT,
	name varchar(100) NOT NULL,/*TODO：唯一*/
	pwd varchar(100) NOT NULL,

	realName varchar(100) not null,
	logoPic varchar(150) not null,
	phone varchar(20) not null,
	address varchar(150) not null,

	primary key(uid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `user` VALUES (null,"wbx@wbx.com","wbx","王博鑫","","15355666666","zju");
INSERT INTO `user` VALUES (null,"1@2.com","qweqwe","苏州","","13055666666","zju");
INSERT INTO `user` VALUES (null,"neirong1@goOneDay.com","neirong","内容发布者","","15888666666","zju");
INSERT INTO `user` VALUES (null,"neirong2@goOneDay.com","neirong","传说内容发布者","","18955666666","zju");

create table schedule(
	sid bigint NOT NULL AUTO_INCREMENT,
	uid bigint NOT NULL,
	title TEXT NOT NULL,
	tag TEXT NOT NULL,
	class int NOT NULL,/*0日程，1是活动*/
	aid bigint NOT NULL,/*如果是活动的话这里链接到那个活动的aid*/
	location TEXT not null,
	startTime TIMESTAMP NOT NULL,
	endTime TIMESTAMP NOT NULL,
	content text not null,
	participant text not null,
	state int not null,/*  0:未完成;1:完成*/
	comment TEXT not null,/*评论*/

	`check` text not null,/*活动的时候为""（空）*/

	logoPic TEXT NOT NULL,

	primary key(sid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `oneday`.`schedule` (`sid`, `uid`, `title`, `tag`,`class`, `location`, `startTime`, `endTIme`, `content`, `check`, `participant`) VALUES (NULL, 1, 'George', 'null',0 ,'zju', '2014-12-25 09:25:32', '2014-12-26 15:36:37', '你好吗', '{"content":"nicaibudao","state":0},{"content":"nice","state":1}}', '[1]');



create table activity(
	aid bigint NOT NULL AUTO_INCREMENT,
	uid bigint NOT NULL,
	title TEXT NOT NULL,
	tag TEXT NOT NULL,
	class int NOT NULL,/*0，1被日程占用，2是学校活动，3是社团活动，4是兴趣活动*/
	location TEXT not null,
	startTime TIMESTAMP NOT NULL,
	endTime TIMESTAMP NOT NULL,
	content text not null,
	participant text not null,
	state int not null,/*  0:未完成;1:完成*/
	comment TEXT not null,/*评论*/

	logoPic TEXT NOT NULL,
	templateNo int not null,/*模板编号*/
	brief varchar(200) not null,/*活动的摘要内容 TODO:字数待定*/
	heat bigint not null,/*热度*/

	primary key(aid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `oneday`.`activity` (`aid`, `uid`, `title`, `tag`,`class`, `location`, `startTime`, `endTIme`, `content`, `participant`) VALUES (NULL, 1, '程序员沙龙', 'null',3 ,'zju', '2014-12-25 09:25:32', '2014-12-26 15:36:37', '这是一个活动', '[1,2,3]');