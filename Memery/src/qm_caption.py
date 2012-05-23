from bs4 import BeautifulSoup as bs
import urlparse
from urllib2 import urlopen
from urllib import urlretrieve
import os
import re
import auxiliary

class QM_Caption:

    def __init__(self, image_source, meme):
        self.image_source = image_source  
        self.soup = bs(urlopen(self.get_url()))
        self.meme = meme
        self.crawl_time = auxiliary.get_datetime()

    def get_filename(self):
        return self.image_source.split("/")[-1]

    def get_qm_id(self):
        return self.get_filename().split(".")[0]

    def get_url(self):
        """Returns the URL of the caption's quickmeme page"""
        return "http://www.quickmeme.com/meme/" + self.get_qm_id() + "/"

    def retrieve_image(self, out_folder):
        """Take in a image_tag of type BeautifulSoup.tag and a parsed_url as a list
            and saves the image source to out_folder"""

        try:
            outpath = os.path.join(out_folder, self.get_filename())
            urlretrieve(self.image_source, outpath)
        except IOError as e:
            if e.errno == 54:
                print str(e) + " ---- " + "Retrying request."
                self.retrieve_image(out_folder)
            if e.errno == 2:
                os.makedirs(out_folder)
                urlretrieve(self.image_source, outpath)
            else:
                raise

    def get_rating(self):
        rating_tag = self.soup.find(id="voting_rating")
        rating_re = re.compile("\nRating: (.+)\n")
        rating = rating_re.match(rating_tag.get_text()).group(1)
        rating = int(auxiliary.remove_commas(rating))
        return rating

    def get_views(self):
        views_tag = self.soup.find(id="voting_views")
        views_re = re.compile("\n(.+) views.*\n")
        views = views_re.match(views_tag.get_text()).group(1)
        views = int(auxiliary.remove_commas(views))
        return views
