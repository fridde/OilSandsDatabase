main.dir = getwd()


pkgs = c("ggplot2", "maps", "scales", "stringr")
for (pkg in pkgs){
  if(!library(pkg, logical.return = TRUE, character.only = TRUE)){
    install.packages(pkg) 
  }
  library(pkg, character.only = TRUE)
}

cbPalette <- c("#999999", "#E69F00", "#56B4E9", "#009E73", "#F0E442", "#0072B2", "#D55E00", "#CC79A7")

files = list.files(pattern =".csv")
#file.name = files[3]


for (file.name in files){
  setwd(main.dir)
  dt = read.table(file.name, sep= ";", blank.lines.skip = FALSE, header = TRUE)
  dt$Compilation = str_replace_all(dt$Compilation, "[\r\n]", "")
  dt$Date = as.Date(dt$Date)
  years = seq(from = as.integer(format(min(dt$Date), "%Y")),
              to = as.integer(format(max(dt$Date), "%Y")) + 5, by = 5)
  
  for(start.year in years){
    for (end.year in years){
        dt = subset(dt, Date > as.Date(paste(start.year, "-01-01", sep ="")))
        dt = subset(dt, Date < as.Date(paste(end.year, "-12-31", sep = "")))
        
        if(dim(dt)[1] > 1){
          table.name = str_split(file.name, "\\.")[[1]][1]
          setwd(main.dir)
          dir.create("figures", showWarnings = FALSE)
          path = paste("figures/", start.year,"_", end.year, sep = "")
          dir.create(path, showWarnings = FALSE)
          setwd(path)
          
          pdf(file = paste(table.name, ".pdf", sep = ""), width= 15)
          
          thinned <- floor(seq(from=1,to=dim(dt)[1],length=20))
          p = ggplot(dt, aes(Date, Value, colour= Compilation, group = Compilation))
          p = p + geom_point(data=dt[thinned,],aes(as.Date(Date), Value, colour= Compilation, shape = Compilation), size = 5)
          p = p + scale_shape_manual(values = seq(0,20), guide=FALSE)
          p = p + geom_line()
          p = p + scale_colour_manual(values=cbPalette)
          p = p + scale_size(range=c(0.5, 1.2), guide=FALSE)
          p = p + scale_y_continuous(labels = comma)
          p = p + ylab("Barrels per day") + xlab("")
          p = p + theme( legend.text = element_text(size = 8, hjust = 5, vjust= -5)) #legend.position="bottom"
          plot(p)
          
          dev.off()
        }
    }
  }
  
  
  
  
  
}