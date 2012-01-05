#! /bin/bash

for f in tmp/arrow*.png ; do 
	convert -verbose   $f -antialias -resample 12x12 -sample 12x12 -extent 16x18-2-3 ../sprites-src/$f  ; 
done 

