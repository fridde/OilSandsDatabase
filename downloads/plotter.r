main.dir = getwd()


pkgs = c("ggplot2", "maps", "scales", "stringr")
for (pkg in pkgs){
  if(!library(pkg, logical.return = TRUE, character.only = TRUE)){
    install.packages(pkg) 
  }
  library(pkg, character.only = TRUE)
}

interval.to.plot = 10

files = list.files(pattern =".csv")
#file.name = files[1]


for (file.name in files){
  setwd(main.dir)
  file.dt = read.table(file.name, sep= ";", blank.lines.skip = FALSE, header = TRUE)
  file.dt$Date = as.Date(file.dt$Date)
  years = seq(from = as.integer(format(min(file.dt$Date), "%Y")),
              to = as.integer(format(max(file.dt$Date), "%Y")) + interval.to.plot,
              by = interval.to.plot)
  
  for(start.year in years){
    for (end.year in years){
        dt = file.dt
        dt = subset(dt, Date > as.Date(paste(start.year, "-01-01", sep ="")))
        dt = subset(dt, Date < as.Date(paste(end.year, "-12-31", sep = "")))
        
        if(dim(dt)[1] > 1 && start.year != end.year){
          nr.of.Compilations = length(unique(dt$Compilation))
          table.name = str_split(file.name, "\\.")[[1]][1]
          setwd(main.dir)
          dir.create("figures", showWarnings = FALSE)
          path = paste("figures/", start.year,"_", end.year, sep = "")
          dir.create(path, showWarnings = FALSE)
          setwd(path)
          
          pdf(file = paste(table.name, ".pdf", sep = ""), width= 15)
          
          thinned <- floor(seq(from=1,to=dim(dt)[1],length=20))
          p = ggplot(dt, aes(Date, Value, colour= Compilation, group = Compilation))
          p = p + geom_point(data=dt[thinned,],aes(as.Date(Date), Value, 
                                                   colour= Compilation, shape = Compilation), size = 5, guide = FALSE)
          p = p + scale_shape_manual(values = seq(0,20))
          p = p + geom_line(aes(linetype=Compilation))
          p = p + scale_color_brewer(palette="Spectral")
#           if(nr.of.Compilations > 1){
#             p = p + scale_colour_gradient(colours = rainbow(nr.of.Compilations))
#           }
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