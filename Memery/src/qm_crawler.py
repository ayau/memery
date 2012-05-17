from bs4 import BeautifulSoup as bs
import urlparse
from urllib2 import urlopen
from qm_meme import QM_Meme
import datetime

class QM_Crawler:

    def __init__(self, sort_by):

        #sort_by should either be "newest" or "popular"
        self.sort_by = sort_by
        self.base_url = "http://www.quickmeme.com/memes/"

    def __iter__(self):
        """Iterates through the memes in quickmeme's database in the order set by sort_by"""

        return self.meme_name_iterator()

    def meme_name_iterator(self):
        """Iterates through the memes in quickmeme's database in the order set by sort_by"""

        page = 1
        self.full_url = self.base_url + self.sort_by + "/" + str(page) + "/"

        soup = bs(urlopen(self.full_url))
        image_tags = soup.findAll("img")

        while image_tags:
            for image_tag in image_tags:
                meme_name = "-".join(image_tag["alt"].split(" "))
                yield QM_Meme(meme_name)
            page+=1
            self.full_url = self.base_url + self.sort_by + "/" + str(page) + "/"        
            soup = bs(urlopen(self.full_url))
            image_tags = soup.findAll("img")

if __name__ == "__main__":

    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    crawler = QM_Crawler("newest")
    for meme in crawler:
        print "crawling: " + meme.meme_name
        for caption in meme:
            print "caption: " + caption.get_qm_id()
            caption.retrieve_image("crawl at " + now)
            
