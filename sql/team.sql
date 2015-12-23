DROP TABLE IF EXISTS teams;
CREATE TABLE teams (
    id INT(11) AUTO_INCREMENT NOT NULL,
    name VARCHAR(150) NOT NULL,
    PRIMARY KEY (id)
);

DROP TABLE IF EXISTS team_members;
CREATE TABLE team_members (
    memberid INT(11) NOT NULL,
    teamid INT(11) NOT NULL
);
