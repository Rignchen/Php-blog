create table if not exists 'users' (
    'id' integer primary key autoincrement,
    'username' varchar(50) not null,
    'password' varchar(100) not null,
    'email' varchar(100) not null,
    'created_at' datetime not null default current_timestamp,
);
create table if not exists 'posts' (
    'user_id' integer not null,
    'title' varchar(100) not null,
    'content' text not null,
    'created_at' datetime not null default current_timestamp,
    primary key ('title', 'user_id'),
    foreign key ('user_id') references 'users' ('id')
);
/*
create table if not exists 'comments' (
    'id' integer primary key autoincrement,
    'content' text not null,
    'created_at' datetime not null default current_timestamp,,
    'user_id' integer not null,
    'post_id' integer not null,
    foreign key ('user_id') references 'users' ('id'),
    foreign key ('post_id') references 'posts' ('id')
);
create table if not exists 'tags' (
    'id' integer primary key autoincrement,
    'name' varchar(50) not null
);
*/


/*
    relationships
 */


 /*
create table if not exists 'likes' (
    'is_liked' boolean not null,
    'user_id' integer not null,
    'post_id' integer not null,
    primary key ('user_id', 'post_id'),
    foreign key ('user_id') references 'users' ('id'),
    foreign key ('post_id') references 'posts' ('id')
);
create table if not exists 'post_tags' (
    'post_id' integer not null,
    'tag_id' integer not null,
    primary key ('post_id', 'tag_id'),
    foreign key ('post_id') references 'posts' ('id'),
    foreign key ('tag_id') references 'tags' ('id')
);
create table if not exists 'follows' (
    'follower_id' integer not null,
    'followed_id' integer not null,
    primary key ('follower_id', 'followed_id'),
    foreign key ('follower_id') references 'users' ('id'),
    foreign key ('followed_id') references 'users' ('id')
);
  */