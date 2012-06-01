import os

## FOR USER TO ENTER ##

path = "/Users/Josh/Dropbox/Personal/Programming/Projects/Memery/CLASSIFY_MEMES/JOSH/"
file_name = "Josh.txt"

##                   ##

print "START"

out_file = open(path+file_name, "w")

dirList = os.listdir(path)
for pic_name in dirList:
    if pic_name[0]==".": continue

    #strip out the extension
    pic_name = pic_name.split(".")[0]
    out_file.write(pic_name + ";"+"TOP;BOTTOM;MEME\n")

out_file.close() 

print "DONE"
