import re
import os
from pathlib import Path

file_path = Path(__file__).parent

def js_array_to_php_assoc_array(data):
    pattern = r""
    data = data.replace("\n","\\n")
    data = re.sub(r"\{(.*?)\}", r"\g<1>", data)
    print(f"first part: {data}")
    pair = r"(\s+|\{)['\"]?(\w+)['\"]?:(.*?)['\"]?(\w+)['\"]?(\\n|,|,\\n)?(.*?)(\s+|\})"
    key = r"\g<2>"
    value = r"\g<4>"
    print(re.sub(pair, key + r"=>" + value + ",", data))

mock = """{
    'key': 'value',
    "key2": "value2",
    key3: 45 
},{
    "more": "more1",
    "and": 53
}"""

js_array_to_php_assoc_array(mock)