
SET FOREIGN_KEY_CHECKS =0;
DROP TABLE IF EXISTS `athletes`;
DROP TABLE IF EXISTS `athlete_position`;
DROP TABLE IF EXISTS `positions`;
DROP TABLE IF EXISTS `teams`;
DROP TABLE IF EXISTS `team_coach_setup`;
DROP TABLE IF EXISTS `coaches`;
DROP TABLE IF EXISTS `position_coach_team`;
SET FOREIGN_KEY_CHECKS =1;



CREATE TABLE `teams` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,  
  `age_group` varchar(255) ,
  `level` int(11) ,
  PRIMARY KEY (`id`),
  UNIQUE (`name`)
)ENGINE=InnoDB;

CREATE TABLE `athletes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,  
  `last_name` varchar(255) NOT NULL,
  `age` int(11) ,
  `teamID` int(11) ,
  PRIMARY KEY (`id`),
  CONSTRAINT teamID_on_teams_ath FOREIGN KEY (`teamID`) REFERENCES `teams` (`id`)
)ENGINE=InnoDB;

CREATE TABLE `coaches` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `first_name` varchar(255) NOT NULL,  
  `last_name` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT fname_lname_Unique_co UNIQUE (`first_name`, `last_name`)
)ENGINE=InnoDB;

CREATE TABLE `team_coach_setup` (
  `teamID` int(11) NOT NULL ,
  `coachID` int(11) NOT NULL ,
  PRIMARY KEY (`teamID`, `coachID`),
  CONSTRAINT coachID_on_coaches_tcs FOREIGN KEY (`coachID`) REFERENCES `coaches` (`id`),
  CONSTRAINT teamID_on_teams_tcs FOREIGN KEY (`teamID`) REFERENCES `teams` (`id`)
)ENGINE=InnoDB;

CREATE TABLE `positions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(255) NOT NULL, 
  PRIMARY KEY (`id`)
)ENGINE=InnoDB;

CREATE TABLE `athlete_position` (
  `athleteID` int(11) NOT NULL ,
  `positionID` int(11) NOT NULL ,
  PRIMARY KEY (`athleteID`, `positionID`),
  CONSTRAINT athleteID_on_athlete_ap FOREIGN KEY (`athleteID`) REFERENCES `athletes` (`id`),
  CONSTRAINT positionID_on_position_ap FOREIGN KEY (`positionID`) REFERENCES `positions` (`id`)
)ENGINE=InnoDB;

CREATE TABLE `position_coach_team` (
  `coachID` int(11) NOT NULL ,
  `positionID` int(11) NOT NULL ,
  `teamID` int(11) NOT NULL ,
  PRIMARY KEY (`coachID`, `positionID`, `teamID`),
  CONSTRAINT coachID_on_coaches_pct FOREIGN KEY (`coachID`) REFERENCES `coaches` (`id`),
  CONSTRAINT positionID_on_position_pct FOREIGN KEY (`positionID`) REFERENCES `positions` (`id`),
  CONSTRAINT teamID_on_teams_pct FOREIGN KEY (`teamID`) REFERENCES `teams` (`id`)
)ENGINE=InnoDB;


insert into `positions` (`type`) values 
('Right Side Hitter'), ('Middle Hitter'), ('Opposite'), ('Setter'), ('Middle Blocker'), ('Outside Hitter'), ('Head Coach'), ('Assistant Coach');


insert into `coaches` (`first_name`,`last_name`) values 
('John', 'Linkin'),
('Marvin', 'Smith');

insert into `teams` (`name`,`age_group`,`level`) values 
('Bears', '14-16', '4'),
('Lions', '17-18', '3');

insert into `athletes` (`first_name`,`last_name`,`age`,`teamID`) values 
('Bobby', 'Joe', '14',(SELECT `id` FROM `teams` WHERE `name` = 'Bears')),
('Peggy', 'Sue', '16',(SELECT `id` FROM `teams` WHERE `name` = 'Bears')),
('Billy', 'Dean', '18',(SELECT `id` FROM `teams` WHERE `name` = 'Lions'));


insert into `team_coach_setup` (`teamID`,`coachID`) values 
((SELECT `id` FROM `teams` WHERE `name` = 'Bears'), (SELECT `id` FROM `coaches` WHERE `first_name` = 'John' AND`last_name` = 'Linkin')),
((SELECT `id` FROM `teams` WHERE `name` = 'Lions'), (SELECT `id` FROM `coaches` WHERE `first_name` = 'Marvin' AND`last_name` = 'Smith'));

insert into `athlete_position` (`athleteID`,`positionID`) values 
((SELECT `id` FROM `athletes` WHERE `first_name` = 'Bobby' AND `last_name` = 'Joe' ), (SELECT `id` FROM `positions` WHERE `type` = 'Right Side Hitter')),
((SELECT `id` FROM `athletes` WHERE `first_name` = 'Peggy' AND `last_name` = 'Sue' ), (SELECT `id` FROM `positions` WHERE `type` = 'Setter')),
((SELECT `id` FROM `athletes` WHERE `first_name` = 'Bobby' AND `last_name` = 'Joe' ), (SELECT `id` FROM `positions` WHERE `type` = 'Outside Hitter'));

insert into `position_coach_team` (`coachID`,`positionID`, `teamID`) values 
((SELECT `id` FROM `coaches` WHERE `first_name` = 'John' AND `last_name` = 'Linkin' ), (SELECT `id` FROM `positions` WHERE `type` = 'Head Coach'), (SELECT `id` FROM `teams` WHERE `name` = 'Bears')),
((SELECT `id` FROM `coaches` WHERE `first_name` = 'John' AND `last_name` = 'Linkin' ), (SELECT `id` FROM `positions` WHERE `type` = 'Assistant Coach'), (SELECT `id` FROM `teams` WHERE `name` = 'Lions')),
((SELECT `id` FROM `coaches` WHERE `first_name` = 'Marvin' AND `last_name` = 'Smith' ), (SELECT `id` FROM `positions` WHERE `type` = 'Head Coach'), (SELECT `id` FROM `teams` WHERE `name` = 'Lions'));


