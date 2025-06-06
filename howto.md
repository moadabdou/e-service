## instruction for  the devs 

### to update  your db  
first  drop  or change the name of your db then run  the following  command  (insert your correct data)
´´´bash
mysqldump --default-character-set=utf8mb4 -u root -p --port=3306  eservice < database/schema.sql
´´´
### to export your db updates
run this (you  can tweak it  for your needs)
´´´bash

mysqldump --default-character-set=utf8mb4 -u root -p --port=3307  eservice > database/schema.sql

or

mysqldump --default-character-set=utf8mb4 -u root -p --port=3306  eservice > database/schema.sql

´´´

