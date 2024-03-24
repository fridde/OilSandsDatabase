# OilSandsDatabase

[Introduction ](#introduction)
| [Adding or updating content](#adding-or-updating-content)
| [Table description](#the-different-tables-in-the-database)
| [Exporting data](#exporting-data)

## Introduction
The **OilSandsDatabase** is an online application written mainly in PHP and some JavaScript including jQuery. Its purpose is to collect all available sources reporting and prognosing Oil Sands Production in Canada in one place, making it easier to compare and evaluate each data set. 

In case you are viewing this document embedded in the website, the whole code can be found at [Github](https://github.com/fridde/OilSandsDatabase).

### A note of caution
This code has been built by a self-learned programmer. The code seldomly follows good standards and there are some severe design-flaws regarding flexibility. The code resembles a learning process. If rebuilt from scratch, **OilSandsDatabase** would look different. If you intend on contributing or changing things, let me know! I'll be happy to rebuild any part that needs more transparency.

## Adding or updating content 

Good tools to use are (in order of importance)
* [Notepad++](http://notepad-plus-plus.org/download)
* [WebPlotDigitizer][WebPlotDigitizer]
* [Text Mechanic](http://textmechanic.com/)
* [OpenRefine][OpenRefine]
* [HTMLMerger](http://www.iterati.org/ebookTools/vHtmlMerger/)

Generally, the steps to add a new source of data to the database are as follows:
  
1. Find a table or graph that you want to include in the database.
2. On the webbpage, go to Sources->Add Source.
3.  Table or graph?

  > If it's a table: Copy-and-paste the table into the textfield.
  > If it's a graph: Convert the graph to data using _WebPlotDigitizer_ and add the data into the textfield.


### Environment Alberta

* Open [a multiple tab opener](http://www.rapidlinkr.com/)
* Add the links from the upper part of [this file][EnvironmentFile]
* Click on submit while pressing the ctrl-key
* Now a large collection of excel-files should be downloaded to your computer. The tabs that are still open will probably show errors. In this case the excel file could not be downloaded. Check manually for these files.
* Convert all files into csv-format using [a converter](http://xls2csv.genxcrowd.com/download).
* Merge these files into one single file by opening your console, navigating to your folder using `cd "path\to\folder"`, and then entering `copy *.csv collected.txt`
* Make sure all projects mentioned at the end of [this file][EnvironmentFile] are included
* Due to the high risk of lines appearing twice during the process, remove all duplicate lines
* Copy and paste

### OilSandsReview

* Open [OilSandsReview](http://www.oilsandsreview.com/)
* Create 14-days-trial account if needed
* Open the [statistics page](http://www.oilsandsreview.com/statistics/production.asp)
* Choose _Production by Project_
* Download _all_ pages as `html` into one folder
* Open your console, navigate to the folder using `cd "path\to\folder"`, and merge all files into one using `copy *.html collected.txt`
* When editing the source, use the button _htmlToCsv_ to easily convert to a csv-table.

### Energy Statistics Handbook

* Be careful: The months have other units than the years! Create an extra column called _Unit_ and manually add `Thousand Cubic metres per month` (if applicable) where monthly values are given. Where units are omitted, the standard unit of the source (in this case _Thousand Cubic metres per year_) is assumed.

# Behind the scenes
## The different tables in the database

The tables behind the scenes are stored in a MySQL database. Within the database, all tables are prepended by a `osdb_`, so that the table __Sources__ actually is called __osdb_sources__.

The tables within the database are: 
* buttons
* compilations
* data
* errors
* errors\_to\_calculate
* gallery
* logs
* projects
* ranking
* sources
* synonyms
* tags
* working

### Sources
This is the most basic table. Here all values that are important for a certain _Source_ are entered as well as the data itself. 

The data can be in any shape and will initially be stored as a text. By using the buttons, the data should be shaped into a format that better suits the insertion into the database. 

### Buttons
This table contains the code to all the buttons that appear on the __Add sources to database__ page

### Compilations
Contains the name for every compilation of data
### Data
### Errors
### Headers
### Ranking

### Synonyms
### Tags
### Working

## Route of data through the database

![Flow of data](https://raw.github.com/fridde/OilSandsDatabase/master/downloads/flow_of_data001.jpg)

# Exporting data


Good tools to analyze the data more are (in order of importance) 

* [R][R] with [RStudio][RStudio]
  * Packages _ggplot2_   
* [TableauPublic][TableauPublic]

## Using R to plot and analyze the data

### Preparations

Comma-seperated-values (_csv_) files of collections of time series can be created on the page _Compilations_ and _Ranking_. On _Compilations_ the time series can be composed individually, whereas _Ranking_ provides links to prepared collections of time series.

Using the links to create csv files will automatically download the file to your local pc or prompting you to do so. 
When done downloading, copy all csv files into a seperate folder of your choice. 

Download the [plotter file][Plotter] for R, place it in the same folders as the csv files and start your local [R][R] installation or [RStudio][Rstudio]. 

In R, change the working directory to the folder where you placed the csv files using the command `setwd("path/to/directory")`. Use citations and your own path, of course.

Now you can _"convert"_ every csv file into plotted graphs by using the single command `source("plotter.r")`, which will execute the file _plotter.r_.

### What does plotter.r do?

1. Loads and/or installs the packages needed (make sure you have an internet connection the first time you run the script).
2. Looks for csv files in the same directory.
3. Creates a folder _"figures"_ (if csv files exist)
3. For every csv file, the script creates several plots with varying ranges of time

## Using Excel or Google docs to analyze data

## Things to do


---
* [Original place for this file][Readme]
* [How to edit this file using Markdown](https://github.com/fletcher/MultiMarkdown/blob/master/Documentation/Markdown%20Syntax.md)

[OpenRefine]: http://openrefine.org/
[WebPlotDigitizer]: http://arohatgi.info/WebPlotDigitizer/app/
[EnvironmentFile]: https://raw.githubusercontent.com/fridde/OilSandsDatabase/master/downloads/Environment%20Alberta%20Sources.txt
[TableauPublic]: https://www.tableausoftware.com/products/public
[R]: http://cran.r-project.org/
[RStudio]: http://www.rstudio.com/
[Readme]: https://github.com/fridde/OilSandsDatabase/blob/master/README.md
[Plotter]: http://www.hehl.se/oilsandsdatabase/download.php?fileName=plotter.r
