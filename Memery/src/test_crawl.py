from qm_crawler import QM_Crawler
import DatabaseConnection
import auxiliary


sort_by = "popular"
crawler = QM_Crawler(sort_by)
#now = datetime.datetime.now().strftime('%Y-%m-%d %Hh%Mm%Ss')
#out_folder = "Crawl of " + sort_by + " at " + now

print "START"

for caption in crawler.crawl(30,5):
    print caption.get_qm_id(), "--", caption.meme.meme_name
    image_source = caption.image_source
    print image_source
    print DatabaseConnection.insertCrawlData(image_source, image_source)

print "DONE"
