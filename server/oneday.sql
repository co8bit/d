CREATE USER 'onedayt54w'@'localhost' IDENTIFIED BY 'onedaysfkl76jk';
GRANT USAGE ON *.* TO 'onedayt54w'@'localhost' IDENTIFIED BY 'onedaysfkl76jk' WITH MAX_QUERIES_PER_HOUR 0 MAX_CONNECTIONS_PER_HOUR 0 MAX_UPDATES_PER_HOUR 0 MAX_USER_CONNECTIONS 0;
CREATE DATABASE IF NOT EXISTS `oneday`;
GRANT ALL PRIVILEGES ON `oneday`.* TO 'onedayt54w'@'localhost';

/*使用数据库*/
use oneday;

/*创建表*/
create table user(
	uid bigint not null AUTO_INCREMENT,/*用户主键*/
	name varchar(100) not null,/*用户名，TODO：唯一*/
	pwd varchar(100) not null,/*用户密码*/

	zhonglei int Default 0,/*用户类别，0：普通，1：社团，2：学校官方，3：商家*/

	realName varchar(100) not null,/*用户真实信息*/
	logoPic varchar(150) not null,/*用户头像*/
	phone varchar(20) not null,/*用户电话*/
	address varchar(150) not null,/*用户地址*/

	smsBalance	bigint	not null,/*短信的余额*/

	zanTable text not null,/*这个uid都赞过哪些的表*/


	/*第三方用户信息*/
	state varchar(4) DEFAULT 0,/*0:未认证；1：通过*/
	checkdate datetime not null,/*通过审核的日期*/

	idcard int not NULL,
	email varchar(100) not null,
	corp varchar(100) not null,/*当前任职机构*/
	career varchar(100) not null,/*职务*/
	businessCard varchar(100) not null,/*名片*/
	corpBook varchar(150) not null,/*机构授权书*/

	primary key(uid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `user` VALUES (null,"co8bit@gmail.com","123456",0,"王博鑫","","15355494740","zju",50,"","","","","","","","","");
INSERT INTO `user` VALUES (null,"co8bit","123456",1,"王博鑫","","15355494740","zju",50,"","","","","","","","","");
INSERT INTO `user` VALUES (null,"wbx@wbx.com","9ca633d2c9103309e8ab7f2d20818aef",0,"王博鑫","","15355494740","zju",50,"","","","","","","","","");

create table schedule(
	/*gsid bigint not null AUTO_INCREMENT,/*日程全局主键*/
	/*lsid bigint not null,/*日程客户端主键*/
	sid bigint not null AUTO_INCREMENT,
	uid bigint not null,/*用户主键*/
	title TEXT not null,/*日程标题*/
	tag TEXT not null,/*日程标记*/
	class int not null,/*日程类别，0日程，1是活动，2是课程*/
	aid bigint not null,/*活动主键，如果是活动的话这里链接到那个活动的aid*/
	location TEXT not null,/*日程位置*/
	startTime datetime not null,/*日程开始时间*/
	endTime datetime not null,/*日程结束时间*/
	content text not null,/*日程内容*/
	participant text not null,/*日程参与者*/
	state int not null,/*日程状态，0:未完成;1:完成*/
	comment TEXT not null,/*日程评论*/

	`check` text not null,/*日程检查项，活动的时候为""（空）*/

	logoPic TEXT not null,/*当日程为活动时，活动的logo图片*/

	isSMS boolean not null,/*是否发送过短信*/

	primary key(sid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `oneday`.`schedule` (`sid`, `uid`, `title`, `tag`,`class`, `location`, `startTime`, `endTIme`, `content`, `check`, `participant`) VALUES (NULL, 1, 'George', 'null',0 ,'zju', '2014-12-25 09:25:32', '2014-12-26 15:36:37', '你好吗', '{"content":"nicaibudao","state":0},{"content":"nice","state":1}}', '[1]');



create table activity(
	aid bigint not null AUTO_INCREMENT,/*活动主键*/
	uid bigint not null,/*创建用户的uid*/
	title TEXT not null,/*活动标题*/
	tag TEXT not null,/*活动标记*/
	class int not null,/*活动类别，0，1被日程占用，2是学校活动，3是社团活动，4是兴趣活动*/
	location TEXT not null,/*活动位置*/
	startTime datetime not null,/*活动开始时间*/
	endTime datetime not null,/*活动结束时间*/
	content text not null,/*活动内容*/
	participant text not null,/*活动参与者*/
	state int not null,/*活动状态，0:未完成;1:完成*/
	comment TEXT not null,/*活动评论*/

	logoPic TEXT not null,/*活动logo图片*/
	templateNo int not null,/*活动模板编号*/
	brief varchar(200) not null,/*活动的摘要内容 TODO:字数待定*/
	zan bigint not null,/*赞的数量*/

	picNum int Default 0,/*图片个数*/
	picList text not null,/*图片地址，json格式*/


	primary key(aid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;
INSERT INTO `oneday`.`activity` (`aid`, `uid`, `title`, `tag`,`class`, `location`, `startTime`, `endTIme`, `content`, `participant`, `logoPic`,`picNum`,`picList`) VALUES (NULL, 1, '程序员沙龙', 'null',3 ,'zju', '2014-12-25 09:25:32', '2014-12-26 15:36:37', '这是一个活动', '[1,2,3]',"/Uploads/20150102/54a6c00ae2115.png",'2','[{"address":"a.jpg"},{"address":"b.jpg"}]');



create table bug(
	bugid bigint not null AUTO_INCREMENT,
	level int not null,/*紧急或重要程度*/
	title TEXT not null,
	class int not null,/*0是bug，1是建议*/
	createTime datetime not null,
	content text not null,/*这里应该是富文本编辑器的内容*/
	state int not null,/*0:未读;1:已读未回复；2：已回复未处理；3：已处理*/
	comment TEXT not null,/*评论*/

	uid bigint not null,
	qq varchar(20) not null,
	email varchar(100) not null,
	phone varchar(20) not null,

	primary key(bugid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;


/**
	用户上了哪些课的表
*/
create table user_course(
	ucid bigint not null AUTO_INCREMENT,
	uid bigint not null,
	cid text not null,/*no、teacher、teamLocation三个hash后的值，指我们APP里的课程唯一标识符*/

	no text not null,/*教务网内的课程号*/
	summary TEXT not null,
	teacher TEXT not null,
	semester varchar(10) not null,
	timeLocation TEXT not null,/*上课时间和地点的json内容*/

	primary key(ucid)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

