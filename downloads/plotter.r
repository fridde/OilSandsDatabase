setwd("C:/Users/Friedrich.Hehl/Dropbox/exjobbet/report/elsevier_format/Performance_records_article/PerformanceRecordsArticle/potential_graphs")
main.dir = getwd()


pkgs = c("ggplot2", "maps", "scales", "stringr")
for (pkg in pkgs){
  if(!library(pkg, logical.return = TRUE, character.only = TRUE)){
    install.packages(pkg) 
  }
  library(pkg, character.only = TRUE)
}
cbPalette = c("#053E8D", "#94A427", "#1DF480", "#F4921B", "#DC3637", "#F40EEB")
cbPalette = c(cbPalette, "#a6cee3", "#1f78b4", "#b2df8a", "#33a02c", "#fb9a99", "#e31a1c", "#fdbf6f", "#ff7f00", "#cab2d6", "#6a3d9a", "#ffff99")
cbPalette = c(cbPalette, grey.colors(10))

files = list.files(pattern =".csv")
file.name = files[6]
time.steps = 10

for (file.name in files){
  print(paste("Plotting for ",file.name))
  setwd(main.dir)
  dt.main = read.table(file.name, sep= ";", blank.lines.skip = FALSE, header = TRUE)
  dt.main$Date = as.Date(dt.main$Date)
  
  from.year = as.integer(format(min(dt.main$Date), "%Y"))
  to.year = as.integer(format(max(dt.main$Date), "%Y")) 
  years = seq(from = from.year - (from.year %% time.steps) , 
              to = to.year + (time.steps - (to.year %% time.steps)), by = time.steps)
  
  for(start.year in years){
    for (end.year in years){
      dt = dt.main
      dt = subset(dt, Date > as.Date(paste(start.year, "-01-01", sep ="")))
      dt = subset(dt, Date < as.Date(paste(end.year, "-12-31", sep = "")))
      # use one of the following lines, comment out the other
      #dt$plotParameter = log(dt$plotParameter) * 2 + 1
      dt$plotParameter = floor(dt$plotParameter)
      
      if(dim(dt)[1] > 365){ # 365 is the number of days during a year
        Compilation.names = unique(dt$Compilation)
        table.name = str_split(file.name, "\\.")[[1]][1]
        setwd(main.dir)
        dir.create("figures", showWarnings = FALSE)
        path = paste("figures/", start.year,"_", end.year, sep = "")
        dir.create(path, showWarnings = FALSE)
        setwd(path)
        
        #pdf(file = paste(table.name, ".pdf", sep = ""), width = 10)
        
        # create a vector of 20 equally spaced points along the time 
        thinned = floor(seq(from=1,to=dim(dt)[1],length=60))  
        p = ggplot(dt, 
                   aes(Date, Value, colour= Compilation, group = Compilation, size = plotParameter), 
                   guide=FALSE)
        p = p + geom_point(data=dt[thinned,],
                           aes(as.Date(Date), Value, colour= Compilation, shape = Compilation),
                           size = 5, guide = Compilation.names)
        p = p + scale_shape_manual(values = seq(0,20), breaks = Compilation.names)
        p = p + geom_line(guide = FALSE, breaks = Compilation.names)
        p = p + scale_colour_manual(values=cbPalette, breaks = Compilation.names)
        p = p + scale_size(range=c(0.6, 2), guide=FALSE)
        p = p + scale_y_continuous(labels = comma)
        p = p + ylab("Barrels per day") + xlab("")
        p = p + theme(legend.text = element_text(size = rel(1.5), hjust = 5, vjust= -5), 
                      axis.title.y = element_text(size = rel(1.5)), 
                      axis.text.y = element_text(size = rel(1.0), colour = "black", face = "bold"),
                      axis.text.x = element_text(size = rel(1.2), colour = "black", face = "bold"),
                      legend.title = element_text(size = rel(1.5)), 
                      legend.direction ="vertical",
                      legend.position="bottom") 
        p = p + scale_fill_discrete(breaks = Compilation.names)
        plot(p)
        
        ggsave(filename = paste(table.name, ".pdf", sep = ""), width = par("din")[1] * 2)
        dir.create("png/", showWarnings = FALSE)
        ggsave(filename = paste("png/", table.name, ".png", sep = ""), width = par("din")[1] * 2)
        #dev.off()
      }
    }
  }
}
setwd(main.dir)
print("Finished!")