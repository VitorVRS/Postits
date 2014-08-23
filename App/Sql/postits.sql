create table users (
  id character varying(100);
  nome character varying(255)
);

create table postits (
  id character varying  PRIMARY KEY,
  title character varying,
  content text,
  color character varying,
  user_id character varying(100)
);