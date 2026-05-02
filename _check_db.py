import subprocess
mysql = r'D:\yingyong\xampp\mysql\bin\mysql.exe'
tables = subprocess.check_output([mysql, '-u', 'root', 'hongdu', '-N', '-e', 'SHOW TABLES;'], text=True).strip().split()
print('Tables:', tables)
found = []
for t in tables:
    cols_res = subprocess.check_output([mysql, '-u', 'root', 'hongdu', '-N', '-e', "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA='hongdu' AND TABLE_NAME='" + t + "' AND DATA_TYPE IN ('varchar','text','longtext','char','tinytext','mediumtext');"], text=True).strip()
    if not cols_res:
        continue
    cols = cols_res.split()
    where_parts = ["`" + c + "` LIKE '%恒信资本%'" for c in cols]
    where = ' OR '.join(where_parts)
    sql = "SELECT id FROM " + t + " WHERE " + where + " LIMIT 5;"
    try:
        result = subprocess.check_output([mysql, '--default-character-set=utf8mb4', '-u', 'root', 'hongdu', '-N', '-e', sql], text=True).strip()
        if result:
            found.append((t, result))
    except:
        pass
if found:
    for t, r in found:
        print('FOUND in', t, ':', r[:100])
else:
    print('DATABASE_CLEAN')
