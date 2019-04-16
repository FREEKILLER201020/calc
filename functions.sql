create table Events
  (id int NOT NULL AUTO_INCREMENT primary key,
  data date,
  name varchar(1000));

create table Member
  (id int NOT NULL AUTO_INCREMENT primary key,
  name varchar(1000));

create table Participation
  ( event_id int,
  member_id int,
  FOREIGN KEY (event_id)
  REFERENCES Events(id)
  ON DELETE CASCADE,
  FOREIGN KEY (member_id)
  REFERENCES Member(id)
  ON DELETE CASCADE);

create table Purchase
  (id int NOT NULL AUTO_INCREMENT primary key,
  name varchar(1000),
  price float,
  quantity float,
  total float,
  buyer int,
  FOREIGN KEY (buyer)
  REFERENCES Member(id)
  ON DELETE CASCADE);

create table Buy
  ( event_id int,
  purchase_id int,
  FOREIGN KEY (event_id)
  REFERENCES Events(id)
  ON DELETE CASCADE,
  FOREIGN KEY (purchase_id)
  REFERENCES Purchase(id)
  ON DELETE CASCADE);

create table Eat_drink
  ( purchase_id int,
  member_id int,
  FOREIGN KEY (purchase_id)
  REFERENCES Purchase(id)
  ON DELETE CASCADE,
  FOREIGN KEY (member_id)
  REFERENCES Member(id)
  ON DELETE CASCADE);

DELIMITER $$
CREATE PROCEDURE AddEvent (IN datte date, IN namme text(500))
BEGIN
INSERT INTO Events (data,name) values (datte, namme);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetEvents ()
BEGIN
Select * from Events order by data desc;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE DelEvent (in idd int)
BEGIN
delete from Events where id=idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE AddMember (IN namme text(500))
BEGIN
INSERT INTO Member (name) values (namme);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE WhoIs (IN idd int)
BEGIN
select * from Member where id=idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetMembers ()
BEGIN
Select * from Member order by name asc;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE AddPurchase (in event_idd int,IN namme text(500), IN pricce float, in quantitty float,in memberr int)
BEGIN
DECLARE p_id integer;
INSERT INTO Purchase (name,price,quantity,total,buyer) values (namme,pricce,quantitty,pricce*quantitty,memberr);
SELECT LAST_INSERT_ID() INTO p_id;
INSERT INTO Buy (event_id,purchase_id) values (event_idd,p_id);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE DelPurchase (in idd int)
BEGIN
delete from Purchase where id=idd;
delete from Buy where purchase_id=idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE DelMember (in idd int)
BEGIN
delete from Member where id=idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE AddParticipation (IN event_idd int, IN member_idd int)
BEGIN
INSERT INTO Participation (event_id,member_id) values (event_idd,member_idd);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetParticipations ()
BEGIN
select * from Participation;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetParticipationsIn (in event_idd int)
BEGIN
DECLARE v_finished INTEGER DEFAULT 0;
DECLARE idd integer;
-- declare cursor for employee email
DEClARE ids_cursor CURSOR FOR
SELECT DISTINCT member_id FROM Participation where event_id=event_idd;
-- declare NOT FOUND handler
DECLARE CONTINUE HANDLER
FOR NOT FOUND SET v_finished = 1;
CREATE TEMPORARY TABLE Member_t LIKE Member;
OPEN ids_cursor;
get_email: LOOP
FETCH ids_cursor INTO idd;
IF v_finished = 1 THEN
LEAVE get_email;
END IF;
-- build email list
INSERT into Member_t select * FROM Member where id=idd;
END LOOP get_email;
CLOSE ids_cursor;
-- select orderr;
-- select * from Players_t order by nick;
select * from Member_t;
DROP TEMPORARY TABLE Member_t;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE DellParticipation (IN event_idd int, IN member_idd int)
BEGIN
DELETE from Participation where event_id=event_idd and member_id=member_idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE AddBuy (IN event_idd int, IN purchase_idd int)
BEGIN
INSERT INTO Participation (event_id,purchase_id) values (event_idd,purchase_idd);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetPurchases (in event_idd int)BEGIN
DECLARE v_finished INTEGER DEFAULT 0;
DECLARE idd integer;
-- declare cursor for employee email
DEClARE ids_cursor CURSOR FOR
SELECT DISTINCT purchase_id FROM Buy where event_id=event_idd;
-- declare NOT FOUND handler
DECLARE CONTINUE HANDLER
FOR NOT FOUND SET v_finished = 1;
CREATE TEMPORARY TABLE Purchase_t LIKE Purchase;
OPEN ids_cursor;
get_email: LOOP
FETCH ids_cursor INTO idd;
IF v_finished = 1 THEN
LEAVE get_email;
END IF;
-- build email list
INSERT into Purchase_t select * FROM Purchase where id=idd;
END LOOP get_email;
CLOSE ids_cursor;
-- select orderr;
-- select * from Players_t order by nick;
select * from Purchase_t;
DROP TEMPORARY TABLE Purchase_t;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE AddEat_drink (IN purchase_idd int, IN member_idd int)
BEGIN
INSERT INTO Eat_drink (purchase_id,member_id) values (purchase_idd,member_idd);
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetEat_drink (IN purchase_idd int)
BEGIN
select * from  Eat_drink where purchase_id=purchase_idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE GetEat_drink_all ()
BEGIN
select * from  Eat_drink;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE DelEat_drink (IN purchase_idd int, IN member_idd int)
BEGIN
Delete from Eat_drink where purchase_id=purchase_idd and member_id=member_idd;
END$$
DELIMITER ;

DELIMITER $$
CREATE PROCEDURE indexx (IN datte date, IN orderr text(500), IN clann_id int)
BEGIN
DECLARE v_finished INTEGER DEFAULT 0;
DECLARE idd integer;
-- declare cursor for employee email
DEClARE ids_cursor CURSOR FOR
SELECT DISTINCT id FROM Players;
-- declare NOT FOUND handler
DECLARE CONTINUE HANDLER
FOR NOT FOUND SET v_finished = 1;
CREATE TEMPORARY TABLE Players_t LIKE Players;
OPEN ids_cursor;
get_email: LOOP
FETCH ids_cursor INTO idd;
IF v_finished = 1 THEN
LEAVE get_email;
END IF;
-- build email list
INSERT into Players_t select * FROM Players where id=idd and timemark<=datte and clan_id=clann_id order by timemark DESC LIMIT 1;
END LOOP get_email;
CLOSE ids_cursor;
-- select orderr;
-- select * from Players_t order by nick;
select * from Players_t order by nick;
DROP TEMPORARY TABLE Players_t;
END$$
DELIMITER ;
