import re
import os
from pathlib import Path

file_name = 'OrderForm.jsx'
file_path = Path(__file__).parent / f'Components/{file_name}'
tmp_path = Path(__file__).parent / f'Components/tmp_{file_name}'

def changeClassFormat(data):
    pattern = r"class(=.*)"
    repl = r"className\g<1>"

    return re.sub(pattern, repl, data)

def changeStyleAttrFormat(data):
    pattern = r"style=[\"\'](.*?)[\"\']"
    
    def replacer(match):
        style_content = match.group(1)
        # Разбиваем по ; и обрабатываем каждое свойство
        properties = style_content.split(';')
        jsx_properties = []
        
        for prop in properties:
            prop = prop.strip()
            if not prop:
                continue
                
            # Разделяем ключ и значение
            if ':' in prop:
                key, value = prop.split(':', 1)
                key = key.strip()
                value = value.strip()
                
                # Преобразуем kebab-case в camelCase
                key_parts = key.split('-')
                camel_key = key_parts[0] + ''.join(p.capitalize() for p in key_parts[1:])
                
                # Обрабатываем значения
                if value.isdigit():
                    value = value  # числа без кавычек
                else:
                    value = f"'{value}'"  # строки в кавычках
                
                jsx_properties.append(f"{camel_key}: {value}")
        
        return "style={{" + ", ".join(jsx_properties) + "}}"
    
    return re.sub(pattern, replacer, data)

def changeArrayFormat(data):
    pattern = r"\[(.*?)\]"
    repl = r"{\g<1>: \g<2>}"

    def replacer(match):
        pattern = r'(".*?"|\'.*?\')\s?=>\s?(".*?"|\'.*?\')'
        return re.sub(pattern, r'{\1: \2}', match.group(1))
    
    return re.sub(pattern, replacer, data)

def changeLinkFormat(data):
    pattern = r"<a(.*?)>"

    data = re.sub(pattern, r"<Link\1>", data)
    return re.sub(r"</a>", r"</Link>", data)

def changePhpAttrFormat(data):
    pattern = r'<\?\=\s*(.+?)\s*\?>'
    
    def replacer(match):
        php_expr = match.group(1)
        
        # Заменяем $article['likes'] на article.likes
        # Обрабатываем как с кавычками, так и без
        jsx_expr = re.sub(r'\$([a-zA-Z_][a-zA-Z0-9_]*)\[\'([^\']+)\'\]', r'\1.\2', php_expr)
        jsx_expr = re.sub(r'\$([a-zA-Z_][a-zA-Z0-9_]*)\["([^"]+)"\]', r'\1.\2', jsx_expr)
        
        # Обрабатываем простые переменные $var -> var
        jsx_expr = re.sub(r'\$([a-zA-Z_][a-zA-Z0-9_]*)', r'\1', jsx_expr)
        
        return '{' + jsx_expr + '}'
    
    return re.sub(pattern, replacer, data, flags=re.DOTALL)
    
if __name__ == '__main__':
    try:
        f = open(file_path, 'r')
        tmp_f = open(tmp_path, 'w')
        data = f.read();
        data = changeClassFormat(data)
        data = changePhpAttrFormat(data)
        data = changeStyleAttrFormat(data)
        data = changeArrayFormat(data)
    
        tmp_f.write(data)
        f.close()
        tmp_f.close()
    except Exception as e:
        print(e)