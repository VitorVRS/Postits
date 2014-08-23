# Postit

This is an basic application for you write your reminders.

You can create many postits and save all of them linking to an google account.

*Initially, the data were saved in localStorage, if you don't want to log in to google account, feel free
to change method Postit::save and Postit::loadAll*

If you have any suggestions, please contact me.


## Installation

* First, you need to create a database em execute "App/Sql/postits.sql" inside the new database.
* Second, you need to configure "App/Database/Database.php" adding connections information to the new database.
* Third, open "index.html" file and configure your Google+ *client_id* 
