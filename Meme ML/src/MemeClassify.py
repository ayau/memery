'''
Created on May 22, 2012

@author: MB
'''
from PIL import Image
import cv2
import math
'''
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
center = getCenterSlice(meme)'''
'--------------------------------------------------------------------------------'
'USE OPENCV TO GET SIFT FEATURES'

d_blue = cv2.cv.RGB(25, 15, 100)
l_blue = cv2.cv.RGB(200, 200, 250)

test_img = cv2.imread("Memes/test.jpg")
obj = cv2.imread("Memes/test.jpg")
'''img2 = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
img = cv2.cvtColor(img2, cv2.COLOR_GRAY2BGR)'''

'Step 1: Detect the keypoints using SIFT Detector'
sift = cv2.FeatureDetector_create('SIFT')
detector = cv2.GridAdaptedFeatureDetector(sift, 1000) # max number of features
'fs_test = sift.detect(test_img)'
keypoints_test = sift.detect(test_img)
keypoints_obj = sift.detect(obj)

'Step 2: Calculate descriptors (feature vectors)'
descriptorextractor = cv2.DescriptorExtractor_create('SIFT')
descriptors_test = descriptorextractor.compute(test_img, keypoints_test)
descriptors_obj = descriptorextractor.compute(obj, keypoints_obj)

'Step 3: Matching descriptor vectors using FLANN matcher'
matcher = cv2.FlannBasedMatcher

'''matches = matcher.match( descriptors_obj, descriptors_test)
max_dist = 0
min_dist = 100

'Quick calculation of max and min distances between keypoints'
for match in matches:
    dist = match.distance
    if dist < min_dist:
        min_dist = dist
    if dist > max_dist:
        max_dist = dist
print "Max dist: ", max_dist
print "Min dist: ", min_dist

'Draw only "good" matches (i.e. whose distance is less than 3*min_dist )'
good_matches = []
for match in matches:
    if match.distance < 3*min_dist:
        good_matches.append(match)

print len(matches),len(good_matches)
'''

'''
for f in fs_test:
    cv2.circle(test_img, (int(f.pt[0]), int(f.pt[1])), int(f.size / 2), l_blue, 2, cv2.CV_AA)
    cv2.circle(test_img, (int(f.pt[0]), int(f.pt[1])), int(f.size / 2), d_blue, 1, cv2.CV_AA)
    ori = math.radians(f.angle)
    tx = math.cos(ori) * 0 - math.sin(ori) * (f.size / 2)
    ty = math.sin(ori) * 0 + math.cos(ori) * (f.size / 2)
    tx += f.pt[0]
    ty += f.pt[1]
    cv2.line(test_img, (int(f.pt[0]), int(f.pt[1])), (int(tx), int(ty)), l_blue, 2, cv2.CV_AA)
    cv2.line(test_img, (int(f.pt[0]), int(f.pt[1])), (int(tx), int(ty)), d_blue, 1, cv2.CV_AA)
'''


cv2.imwrite('TEST.jpg',test_img)
