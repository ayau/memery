#!/usr/bin/python

import sys
import MySQLdb
import datetime

def insertCrawlData(crawl_id, file_name):
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return False
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO crawl_data(image_identifier, created_at, file_name) \
             VALUES ('%d', '%s', '%s')"\
             % (crawl_id, now, file_name)
    
    try:
        cursor.execute(sql)
        db.commit()
    except MySQLdb.IntegrityError:
        print ("duplicate entry")
        db.close()
        return False
    except:
    # Rollback in case there is any error
        db.rollback()
        print ("Something is wrong, database rolled back")
        db.close()
        return False
    
    # disconnect from server
    db.close()
    
    return True

#Insert Meme into database after analyzed
def insertMeme(bg_id, text_top, text_bot, date_crawled, domain, crawl_id):
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return False
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    # TODO create meme_id from stripping punctuations from text and concatenating with bg_id in hex?
    meme_id = ""
    
    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO \
    memes(meme_identifier, background_id, text_top, text_bot, created_at, date_crawled, domain, crawl_id) \
    VALUES ('%s', '%d', '%s', '%s', '%s', '%s', '%s', '%d')" \
    % (meme_id, bg_id, text_top, text_bot, now, date_crawled, domain, crawl_id)
    
    try:
        cursor.execute(sql)
        db.commit()
    except MySQLdb.IntegrityError:
        print ("duplicate entry")
        db.close()
        return False
    except:
    # Rollback in case there is any error
        db.rollback()
        print ("Something is wrong, database rolled back")
        db.close()
        return False
    
    # disconnect from server
    db.close()
    
    return True

#Return list of file_names for all background images
def getAllBackground():
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return -1
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    sql = """SELECT file_name, id from meme_template """
    
    
    cursor.execute(sql)
    output = []
    
    while (1):
        row = cursor.fetchone()
        if row == None:
            break
        else:
            output.append([row[0], row[1]])
    
    
    # disconnect from server
    db.close()
    
    return output

#Return crawl data given id(auto increment)  
#row[0] => file_name, row[1] => processed (0 -> not, 1 -> processed), row[2] => date_crawled
def getCrawlData(crawl_id):
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return
        
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    sql = """SELECT file_name, processed, created_at FROM crawl_data WHERE id = '%s' LIMIT 1""" % (crawl_id)
    
    
    cursor.execute(sql)
    row = cursor.fetchone()
    
    # disconnect from server
    db.close()
    
    if row == None:
        break
    else:
        return row


#Return True when successful. Marks a crawled item as processed
def markAsProcessed(crawl_id):
    # TODO
    # to check whether it is a meme or not. Also deletes the image (if we're storing the image)
    
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return False
        
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    sql = "UPDATE TABLE crawl_data \
    SET processed_at='%s', processed=1 WHERE id=%d" \
    % (now, crawl_id)
    
    try:
        cursor.execute(sql)
    except:
        db.rollback()
        print ("Something is wrong, database rolled back")
        db.close()
        return False
    
    
    # disconnect from server
    db.close()  
    
    return True

#Returns the id of the first unprocessed crawl item. If nothing is unprocessed, returns -1
def getFirstUncraweled():
    
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return
        
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    sql = """SELECT id FROM crawl_data WHERE processed = 0 ORDER BY id ASC LIMIT 1""" 
    
    
    cursor.execute(sql)
    row = cursor.fetchone()
    
    # disconnect from server
    db.close()
    
    if row == None:
        return -1
    else:
        return row[0]

#Inserts new meme template picture into database. file_name => location and name of image, meme_name => scumbag stahl
def insertMemeTemplate(file_name, meme_name):
    
    # TODO
    # Make it save cluster info (1024 characters)
    
    # Open database connection
    try:
        db = MySQLdb.connect("localhost", "root", "", "memery")
    except:
        sys.exit('Cannot get into database')
        return False
        
    # prepare a cursor object using cursor() method
    cursor = db.cursor()
    
    now = datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
    
    # Prepare SQL query to INSERT a record into the database.
    sql = "INSERT INTO meme_template(file_name, meme_name, created_at) \
             VALUES ('%s', '%s', '%s')"\
             % (file_name, meme_name, now)
    
    try:
        cursor.execute(sql)
        db.commit()
    except MySQLdb.IntegrityError:
        print ("duplicate entry")
        db.close()
        return False
    except:
    # Rollback in case there is any error
        db.rollback()
        print ("Something is wrong, database rolled back")
        db.close()
        return False
    
    # disconnect from server
    db.close()
    
    return True
