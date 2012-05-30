'''
Created on May 21, 2012

@author: MB
'''
from pytesser import *
from PIL import Image

meme = Image.open("Memes/10rw.jpg") # open colour image
meme = meme.convert("L")
width = meme.size[0]
height = meme.size[1]
for x in range(width):
    for y in range(height):
        if meme.getpixel((x,y)) < 235:
            meme.putpixel((x,y),0)
top = (0,0,width-1,height/3)
bottom = (0,height*3/4,width-1,height-1)
memetop = meme.crop(top)
memebottom = meme.crop(bottom)
meme.save('result.png')
memetop.save('memetop.png')
memebottom.save('memebottom.png')
text = image_file_to_string('memetop.png', graceful_errors=True)
print text

