drop table if exists t_user;
drop table if exists t_character;

create table t_user (
    usr_id integer not null primary key auto_increment,
    usr_name varchar(50) not null,
    usr_password varchar(88) not null,
    usr_salt varchar(23) not null,
    usr_role varchar(50) not null 
) engine=innodb character set utf8 collate utf8_unicode_ci;
ALTER TABLE `t_user` ADD UNIQUE(`usr_name`); 

create table t_character (
    char_id integer not null primary key auto_increment,
    char_name varchar(500) not null
) engine=innodb character set utf8 collate utf8_unicode_ci;
ALTER TABLE `t_character` ADD UNIQUE(`char_name`); 