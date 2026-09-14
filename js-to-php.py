import re
import os
from pathlib import Path


files_to_format = [
    'Stream.php'
]
file_path = Path(__file__).parent / "frontend/src/Pages/Public"
def change_str_format(data):
    """
    replace the JS formatters ${} to php formatters <?= ?>
    """
    pattern = r"(\$\{)(.*?)(\})"
    repl = r"<?= \g<2> ?>"

    formatted = re.sub(pattern, repl, data)
    return formatted

def give_attributes_double_quotes(data):
    pattern = r"<(\w+)(.*?)?(\w+='(.*?)')(.*?)?>"

def remove_formatting_quotes(data):
    pattern = r"(`)(.*?)(`)"
    repl = r"\g<2>"

    formatted = re.sub(pattern, repl, data)
    return formatted

def change_dots_to_brackets(data):
    """
    change from the dot 'obj.attr' format to the standard bracket obj['attr'] format
    return: the formatted string with the ${} not removed
    """
    pattern = r"(\$\{)\W?(\w+|\w+\[\w+\])(\.(\w+))+((?: \S+)*\})"

    def combine(match):
        match2 = re.match(r"(\$\{)(.*?)((?: \S+)*\})", match.group(0))
        parts = match2.group(2).split('.')
        obj = parts[0]
        attrs = parts[1:]
        return match.group(1) + obj + ''.join(f"['{attr}']" for attr in attrs) + match.group(5)

    return re.sub(pattern, combine, data)

def change_variable_names(data):
    """
    changes pattersn like 'newsData[index]' to $data['news'][index]
    """
    matching_variables = {
        r"newsData": r"$article",
        # r"interviewData": r"$data['interviews']",
        # r"booksData": r"$data['books']",
        # r"eventsData": r"$data['events']",
        r"booksData(\[\d+\])": r"$books\g<1>",
        r"servicesData(\[\d+\])": r"$services\g<1>",
        r"eventsData(\[\d+\])": r"$events\g<1>",
        r"newsData(\[\d+\])": r"$news\g<1>",
        r"interviewData(\[\d+\])": r"$interviews\g<1>",
        r"content(\[\d+\])": r"$music\g<1>",
        r"booksData\[(index|i)\]": r"$book",
        r"servicesData\[(index|i)\]": r"$service",
        r"eventsData\[(index|i)\]": r"$event",
        r"newsData\[(index|i)\]": r"$article",
        r"interviewData\[(index|i)\]": r"$interview",
        r"musicData\[(index|i)\]": r"$track",
        r"content\[(index|i)\]": r"$track",
    }
    formatted = data
    for pattern, repl in matching_variables.items():
        formatted = re.sub(pattern, repl, formatted)
    
    return formatted

def change_array_to_vars(data):
    pattern = r"\$data\[['\"](.*?)['\"]\]"
    repl = r"$\g<1>"

    formatted = re.sub(pattern, repl, data)
    return formatted

def restore_str_format_in_js():
    """
    replace the removed ${} which reside in the script tags
    """

def extract_html_from_js():
    """
    extracts any html created using js
    """
    pattern =r"function(.*)?(\{)"

def format_js_to_php():
    for f in files_to_format:
        file = open(os.path.join(file_path, f), 'r', encoding="utf8")
        tmp_file = open(os.path.join(file_path, f"tmp_{f}"), 'w', encoding="utf8")

        data = file.read()
        data = change_dots_to_brackets(data)
        data = remove_formatting_quotes(data)
        
        data = change_variable_names(data)
        data = change_str_format(data)
        data = change_array_to_vars(data)
        tmp_file.write(data)
        file.close()
        tmp_file.close()

format_js_to_php()