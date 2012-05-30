'''
Created on May 22, 2012

@author: MB
'''
from PIL import Image

def resize(image):
    if image.size is not (310,310):
        image = image.resize((310,310))
    return image

def getCenterSlice(image):
    width = image.size[0]
    mid = image.size[1]/2
    box = (0, mid - 5, width-1, mid + 5) #left, upper, right, lower pixel coordinate
    center = image.crop(box)
    return center


im = Image.open('Memes/1bc1.jpg')
meme = resize(im)
center = getCenterSlice(meme)

