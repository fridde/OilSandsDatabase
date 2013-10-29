#Oil Sands Database

[Introduction ](#introduction)
| [Adding or updating content](#adding-or-updating-content)
| [Table description](#the-different-tables-in-the-database)

##Introduction ##
The Oil Sands Database is an online apllication written mainly in PHP and some JavaScript including jQuery. Its purpose is to collect all available sources reporting and prognosing Oil Sands Production in Canada in one place, making it easier to compare and evaluate each data set. 

## Adding or updating content 

Good tools to use are (in order of importance)
* [Notepad++](http://notepad-plus-plus.org/download)
* [OpenRefine](http://openrefine.org/)
* [Text Mechanic](http://textmechanic.com/)

### Environment Alberta

* Open [a multiple tab opener](http://www.openurls.eu/)
* Add the links from [this file](https://github.com/fridde/OilSandsDatabase/blob/master/helper_files/Environment%20Alberta%20Sources.txt), 20 at a time, into the textfield
* Click on submit while pressing the ctrl-key
* Download every file as an excel-file into the same folder
* Convert all files into csv-format using [a converter](http://xls2csv.genxcrowd.com/download).
* Merge these files into one single file by opening your console, navigating to your folder using `cd "path\to\folder"`, and then entering `copy *.csv collected.txt`
* Make sure all projects mentioned at the end of [this file](https://github.com/fridde/OilSandsDatabase/blob/master/helper_files/Environment%20Alberta%20Sources.txt) are included
* Copy and paste

### OilSandsReview

* Open [OilSandsReview](http://www.oilsandsreview.com/)
* Create 14-days-trial account if needed
* Open the [statistics page](http://www.oilsandsreview.com/statistics/production.asp)
* Choose _Production by Project_
* Download _all_ pages as `html` into one folder
* Open your console, navigate to the folder using `cd "path\to\folder"`, and merge all files into one using `copy *.html collected.txt`

### Energy Statistics Handbook

* Be careful: The months have other units than the years! Create an extra column called _Unit_ and manually add `Thousand Cubic metres per month` (if applicable) where monthly values are given. Where units are omitted, the standard unit of the source (in this case _Thousand Cubic metres per year_) is assumed.

## The different tables in the database ##

The tables behind the scenes are stored in a MYSQL database. Within the database, all tables are prepended by a `osdb_`, so that the table __Sources__ actually is called __osdb_sources__.

The tables within the database are: 
* Buttons
* Compilations
* Data
* Errors
* Headers
* Ranking
* Sources
* Synonyms
* Tags
* Working

###Sources
This is the most basic table. Here all values that are important for a certain _Source_ are entered as well as the data itself. 

The data can be in any shape and will initially be stored as a text. By using the buttons, the data should be shaped into a format that better suits the insertion into the database. 

###Buttons
This table contains the code to all the buttons that appear on the __Add sources to database__ page

###Compilations
Contains the name for every compilation of data
###Data
###Errors
###Headers
###Ranking

###Synonyms
###Tags
###Working

##Things to do

* _Remove tag_ function
* _Remove Source_ function to remove all data associated with a certain task from all tables
* 

---
[How to edit this file using Markdown](https://github.com/fletcher/MultiMarkdown/blob/master/Documentation/Markdown%20Syntax.md)
