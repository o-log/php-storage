array(
'insert into olog_auth_permission (title) values ("PERMISSION_STORAGE_UPLOAD_FILES") /* rand2652403456 */;',
'create table olog_storage_file (id int not null auto_increment primary key, created_at_ts int not null default 0) engine InnoDB default charset utf8 /* rand6400 */;',
'alter table olog_storage_file add column storage_name varchar(255) not null /* rand577368 */;',
'alter table olog_storage_file add column file_path_in_storage varchar(255) not null /* rand577368 */;',
'alter table olog_storage_file add column original_file_name varchar(255) not null /* rand577368 */;',
)
