main.dir = getwd()


pkgs = c("ggplot2", "maps", "scales", "stringr")
for (pkg in pkgs){
  if(!library(pkg, logical.return = TRUE, character.only = TRUE)){
    install.packages(pkg) 
  }
  library(pkg, character.only = TRUE)
}

cbPalette = c("#999999", "#E69F00", "#56B4E9", "#009E73", "#F0E442", "#0072B2", "#D55E00", "#CC79A7")
cbPalette = c(cbPalette, rainbow(10))

files = list.files(pattern =".csv")


for (file.name in files){
  print(paste("Plotting for ",file.name))
  setwd(main.dir)
  dt.main = read.table(file.name, sep= ";", blank.lines.skip = FALSE, header = TRUE)
  dt.main$Date = as.Date(dt.main$Date)
  
  from.year = as.integer(format(min(dt.main$Date), "%Y"))
  to.year = as.integer(format(max(dt.main$Date), "%Y")) 
  years = seq(from = from.year - (from.year %% 5) , 
              to = to.year + (5 - (to.year %% 5)), by = 5)
  
  for(start.year in years){
    for (end.year in years){
        dt = dt.main
        dt = subset(dt, Date > as.Date(paste(start.year, "-01-01", sep ="")))
        dt = subset(dt, Date < as.Date(paste(end.year, "-12-31", sep = "")))
        dt$plotParameter = log(dt$plotParameter) * 2 + 1 
        Compilation.names = unique(dt$Compilation)
        dt = within(dt, Order = factor(Order, levels = names(sort(table(Order)))))
        
        if(dim(dt)[1] > 1){
          table.name = str_split(file.name, "\\.")[[1]][1]
          setwd(main.dir)
          dir.create("figures", showWarnings = FALSE)
          path = paste("figures/", start.year,"_", end.year, sep = "")
          dir.create(path, showWarnings = FALSE)
          setwd(path)
          #print(unique(dt$Compilation))
          
          pdf(file = paste(table.name, ".pdf", sep = ""), width= 15)
          
          # create a vector of 20 equally spaced points along the time 
          thinned = floor(seq(from=1,to=dim(dt)[1],length=20))  
          p = ggplot(dt, aes(Date, Value, colour= Compilation, group = Compilation, size = plotParameter), guide=FALSE)
          p = p + geom_point(data=dt[thinned,],aes(as.Date(Date), Value, colour= Compilation, shape = Compilation), size = 5, guide = Compilation.names)
          p = p + scale_shape_manual(values = seq(0,20))
          p = p + geom_line(guide = FALSE)
          p = p + scale_colour_manual(values=cbPalette)
          p = p + scale_size(range=c(0.5, 2), guide=FALSE)
          p = p + scale_y_continuous(labels = comma)
          p = p + ylab("Barrels per day") + xlab("")
          p = p + theme(legend.text = element_text(size = 8, hjust = 5, vjust= -5)) #legend.position="bottom"
          p = p + scale_fill_discrete(breaks = Compilation.names)
          plot(p)
          
          dev.off()
        }
    }
  }
}
setwd(main.dir)