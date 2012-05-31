from qm_crawler import QM_Crawler
import DatabaseConnection
import auxiliary
from urllib2 import HTTPError


print "START"

sort_by = "popular"
crawler = QM_Crawler(sort_by)
#now = datetime.datetime.now().strftime('%Y-%m-%d %Hh%Mm%Ss')
#out_folder = "Crawl of " + sort_by + " at " + now

def run_test_crawl(crawler_iterator):
    for caption in crawler_iterator:
        print caption.get_qm_id(), "--", caption.meme.meme_name
        image_source = caption.image_source
        print "url:", caption.get_url()
        print "image_source:", image_source 
        print DatabaseConnection.insertCrawlData(caption.get_url(),caption.get_filename(), caption.get_rating(), caption.get_views())
    run_test_crawl(crawler_iterator)   
run_test_crawl(crawler.crawl(30,35))

print "DONE"
