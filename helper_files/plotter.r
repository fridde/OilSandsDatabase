pkgs = c("ggplot2", "maps", "scales")
library("scales")
for (pkg in pkgs){
  if(!library(pkg, logical.return = TRUE)){
    install.packages(pkg) 
  }
  library(pkg)
}

files = list.files(pattern =".csv")



for (file.name in files){
  dt = read.table(file.name, sep= ";", blank.lines.skip = FALSE, header = TRUE)
  thinned <- floor(seq(from=1,to=dim(dt)[1],length=20))
  p = ggplot(dt, aes(as.Date(Date), Value, colour= Compilation, group = Compilation))
  p = p + geom_point(data=dt[thinned,],aes(as.Date(Date), Value, colour= Compilation, shape = Compilation), size = 5)
  p = p + scale_shape_manual(values = seq(0,20))
  p = p + geom_line() + scale_y_continuous(labels = comma)
  p = p + ylab("Barrels per day") + xlab("")
  p = p + theme( legend.text = element_text(size = 8, hjust = 5, vjust= -5)) #legend.position="bottom"
  p
}