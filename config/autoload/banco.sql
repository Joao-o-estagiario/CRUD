use DATABASE blog;

CREATE TABLE post
(
  id serial,
  title text NOT NULL,
  conten text NOT NULL,
  status integer NOT NULL,
  date_created timestamp without time zone NOT NULL
);

CREATE TABLE comment
(
  id serial,
  post_id integer NOT NULL,
  conten text NOT NULL,
  author varchar(128) NOT NULL,
  date_created timestamp without time zone NOT NULL
);

CREATE TABLE post_tag
(
  id serial,
  post_id integer NOT NULL,
  tag_id integer NOT NULL
);

CREATE TABLE tag
(
  id serial,
  name varchar(128)
)