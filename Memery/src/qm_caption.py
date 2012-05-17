from bs4 import BeautifulSoup as bs
import urlparse
from urllib2 import urlopen
from urllib import urlretrieve
import os

class QM_Caption:

    def __init__(self, image_source):
        self.image_source = image_source  

        soup = bs(urlopen(self.get_url()))

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
