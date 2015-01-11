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

	smsBalance	bigint	not null,/*短信的余额*/

	zanTable text not null,/*这个uid都赞过哪些的表*/

	primary key(uid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `user` VALUES (null,"wbx@wbx.com","9ca633d2c9103309e8ab7f2d20818aef","王博鑫","","15355494740","zju",50,"");
INSERT INTO `user` VALUES (null,"1@2.com","efe6398127928f1b2e9ef3207fb82663","苏州","","18868100713","zju",50,"");
INSERT INTO `user` VALUES (null,"neirong1@goOneDay.com","fa5f47fc60ac772d1c74dc8284ba0e9d","内容发布者","","","zju",50,"");
INSERT INTO `user` VALUES (null,"neirong2@goOneDay.com","fa5f47fc60ac772d1c74dc8284ba0e9d","传说内容发布者","","","zju",50,"");

create table schedule(
	sid bigint NOT NULL AUTO_INCREMENT,
	uid bigint NOT NULL,
	title TEXT NOT NULL,
	tag TEXT NOT NULL,
	class int NOT NULL,/*0日程，1是活动*/
	aid bigint NOT NULL,/*如果是活动的话这里链接到那个活动的aid*/
	location TEXT not null,
	startTime datetime NOT NULL,
	endTime datetime NOT NULL,
	content text not null,
	participant text not null,
	state int not null,/*0:未完成;1:完成*/
	comment TEXT not null,/*评论*/

	`check` text not null,/*活动的时候为""（空）*/

	logoPic TEXT NOT NULL,

	isSMS boolean not null,/*是否发送过短信*/

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
	startTime datetime NOT NULL,
	endTime datetime NOT NULL,
	content text not null,
	participant text not null,
	state int not null,/*0:未完成;1:完成*/
	comment TEXT not null,/*评论*/

	logoPic TEXT NOT NULL,
	templateNo int not null,/*模板编号*/
	brief varchar(200) not null,/*活动的摘要内容 TODO:字数待定*/
	zan bigint not null,/*赞的数量*/

	primary key(aid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `oneday`.`activity` (`aid`, `uid`, `title`, `tag`,`class`, `location`, `startTime`, `endTIme`, `content`, `participant`, `logoPic`) VALUES (NULL, 1, '程序员沙龙', 'null',3 ,'zju', '2014-12-25 09:25:32', '2014-12-26 15:36:37', '这是一个活动', '[1,2,3]',"/Uploads/20150102/54a6c00ae2115.png");



create table bug(
	bugid bigint NOT NULL AUTO_INCREMENT,
	level int NOT NULL,/*紧急或重要程度*/
	title TEXT NOT NULL,
	class int NOT NULL,/*0是bug，1是建议*/
	createTime datetime NOT NULL,
	content text not null,/*这里应该是富文本编辑器的内容*/
	state int not null,/*0:未读;1:已读未回复；2：已回复未处理；3：已处理*/
	comment TEXT not null,/*评论*/

	uid bigint NOT NULL,
	qq varchar(20) not null,
	email varchar(100) not null,
	phone varchar(20) not null,

	primary key(bugid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

