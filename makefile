MYSQL = mysql -u tennisthemenice -pasecretpasswordabouttennis tennisapi

data:
	$(MYSQL) < sql/members.sql
	$(MYSQL) < sql/team.sql
	$(MYSQL) < sql/initial_data.sql
