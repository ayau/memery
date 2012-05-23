import datetime

def remove_commas(s):
    split = s.split(",")
    return "".join(split)

def get_datetime():
    """Returns the current date and time"""
    #this function is intended to standardize timestamps throughout the project
    return datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
