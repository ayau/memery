from bs4 import BeautifulSoup as bs
import urlparse
from urllib2 import urlopen
from qm_meme import QM_Meme

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

    def crawl_memes(self, num_memes):
        """Crawls num_memes memes.  E.g., if num_memes=3, crawls 3 memes."""
        meme_count = 0
        for meme in self:
            if meme_count >= num_memes: break
            yield meme
            meme_count+=1

    def crawl(self, num_memes, num_captions):
        """Crawls num_captions captions for num_memes memes"""
        for meme in self.crawl_memes(num_memes):
            for caption in meme.crawl_captions(num_captions):
                yield caption
