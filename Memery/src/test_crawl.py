from qm_crawler import QM_Crawler

sort_by = "popular"
crawler = QM_Crawler(sort_by)
#now = datetime.datetime.now().strftime('%Y-%m-%d %Hh%Mm%Ss')
#out_folder = "Crawl of " + sort_by + " at " + now

for caption in crawler.crawl(1,1):
    a = caption
