from bs4 import BeautifulSoup as bs
import urlparse
from urllib2 import urlopen
from urllib import urlretrieve
import os
import DatabaseConnection

def download_images(url, out_folder):
    ## code from http://stackoverflow.com/questions/257409/download-image-file-from-the-html-page-source-using-python
    """Downloads all the images at 'url' into 'out_folder'"""

    if not os.path.exists(out_folder):
        os.makedirs(out_folder)
    
    parsed_url = list(urlparse.urlparse(url))

    soup = bs(urlopen(url))

    image_tags = soup.findAll("img")
    
    for image_tag in image_tags:
        retrieve_image(image_tag, parsed_url, out_folder)

    return len(image_tags)

def retrieve_image(image_tag, parsed_url, out_folder):
    """Take in a image_tag of type BeautifulSoup.tag and a parsed_url as a list
       and saves the image source to out_folder"""
    try:
        print "Image %(src)s" % image_tag

        filename = image_tag["src"].split("/")[-1]
        parsed_url[2] = image_tag["src"]
        outpath = os.path.join(out_folder, filename)

        if image_tag["src"].lower().startswith("http"):
            if(DatabaseConnection.insertCrawlData(image_tag["src"], image_tag["src"])):
                urlretrieve(image_tag["src"], outpath)
        else:
            urlretrieve(urlparse.urlunparse(parsed_url), outpath)
    except IOError as e:
        if e.errno is 54:
            print str(e) + " ---- " + "Retrying request."
            retrieve_image(image_tag, parsed_url, out_folder)
        else:
            raise

def get_meme_names(sort_by):
    """Gets the names of memes in quickmeme's database.

    sort_by: set to "submissions" to crawl in order of # featured.
             set to "newest" to crawl in order of newest
    
    """

    url_base = "http://www.quickmeme.com/memes/" + sort_by + "/"
    meme_names = []

    for page in range(1, 100):
        full_url = url_base + str(page) + "/"
        print "crawling " + full_url
    
        soup = bs(urlopen(full_url))

        for img in soup.findAll("img"):
            meme_name = "-".join(img["alt"].split(" "))
            print meme_name
            meme_names.append(meme_name)

    return meme_names

def crawl_meme(meme_name):
    for page in range(1, 3):
        url_to_crawl = "http://www.quickmeme.com/" + meme_name + "/popular/" + str(page) + "/"
        print "crawling " + url_to_crawl
        if not download_images(url_to_crawl, "memes"):
            break


print "STARTING MEME CRAWLER"

meme_names = get_meme_names("newest")

for meme_name in meme_names:
    crawl_meme(meme_name)

print "DONE"
    
