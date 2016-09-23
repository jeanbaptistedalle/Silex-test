create database if not exists test character set utf8 collate utf8_unicode_ci;
use microcms;

grant all privileges on test.* to 'test_user'@'localhost' identified by 'secret';