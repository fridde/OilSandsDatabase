pkgs = c("ggplot2", "maps")
for (pkg in pkgs){
  if(!library(pkg, logical.return = TRUE)){
    install.packages(pkg) 
  }
  require(pkg)
}

files = list.files(pattern =".csv")

for (file.name in files){
  dt = read.table(file.name, sep= ";", blank.lines.skip = FALSE, header = TRUE)
  qplot(Date, Value, data = dt, colour = Compilation)
}