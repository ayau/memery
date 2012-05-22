import datetime

def remove_commas(s):
    split = s.split(",")
    return "".join(split)

def get_time():
    return datetime.datetime.now().strftime('%Y-%m-%d %H:%M:%S')
