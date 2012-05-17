from bs4 import BeautifulSoup as bs
import urlparse
from urllib2 import urlopen
from qm_caption import QM_Caption


class QM_Meme:

    def __init__(self, meme_name):
        self.meme_name = meme_name
        self.url_base = "http://www.quickmeme.com/" + meme_name + "/popular/"
        self.page = 1

    def __iter__(self):
        return self.meme_iterator()

    def meme_iterator(self):

        page = 1
        self.page_url = self.url_base + str(page) + "/"
        parsed_url = list(urlparse.urlparse(self.page_url))
        soup = bs(urlopen(self.page_url))
        image_tags = soup.findAll("img")

        while image_tags:
            for image_tag in image_tags:
                image_source = image_tag["src"]      
                yield QM_Caption(image_source, self)
            page+=1
            self.page_url = self.url_base + str(page) + "/"
            parsed_url = list(urlparse.urlparse(self.page_url))
            soup = bs(urlopen(self.page_url))
            image_tags = soup.findAll("img")

    def crawl_captions(self, num_captions):
        """Crawl num_caption captions.  E.g., if num_captions = 4, will crawl 4 captions"""

        caption_count = 0
        for caption in self:
            if caption_count >= num_captions: break
            yield caption
            caption_count+=1
