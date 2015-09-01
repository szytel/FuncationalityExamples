Janrain Custom Portal Validation
==============

This code is an example of how to build a portal that a help desk type of role could log into and udate user records

index.php
- Uses the entity.find call to get user records that meet a certain criteria
-- In this case, it is whether "validationStatus='pending'"

update-record.php
- Once a record is clicked on, attributes within the record can be updated
