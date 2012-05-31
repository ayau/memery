import datetime

def remove_commas(s):
    return s.replace(",","")

def get_datetime():
    """Returns the current date and time"""
    #this function is intended to standardize timestamps throughout the project
    return datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
