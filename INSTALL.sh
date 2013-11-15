#! /bin/sh

echo "\nROOT access required to MySql."
mysql -u root -p --local-infile=1 < make_database.sql

exit 0
