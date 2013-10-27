Oil Sands Database
=====================

Adding or updating content
---------------------
Good tools to use are (in order of importance)
* [Notepad++](http://notepad-plus-plus.org/download)
* [OpenRefine](http://openrefine.org/)


### Environment Alberta

* Open [a multiple tab opener](http://www.openurls.eu/)
* Add the links from [this file](https://github.com/fridde/OilSandsDatabase/blob/master/helper_files/Environment%20Alberta%20Sources.txt), 20 at a time, into the textfield
* Click on submit while pressing the ctrl-key
* Download every file as an excel-file into the same folder
* Convert all files into csv-format using [a converter](http://xls2csv.genxcrowd.com/download).
* Merge these files into on single file by opening your console, navigating to your folder using `cd "path\to\folder"`, and then entering `copy *.csv collected.txt`
* Copy and paste

### OilSandsReview

* Open [OilSandsReview](http://www.oilsandsreview.com/)
* Create 14-days-trial account if needed
* Open the [statistics page](http://www.oilsandsreview.com/statistics/production.asp)
* Choose _Production by Project_
* Download _all_ pages as `html` into one folder
* Open your console, navigate to the folder using `cd "path\to\folder"`, and merge all files into one using `copy *.html collected.txt`

---
[How to edit this file using Markdown](https://github.com/fletcher/MultiMarkdown/blob/master/Documentation/Markdown%20Syntax.md)
