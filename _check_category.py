import os
import os
p = os.path.join('D:\\yingyong\\xampp\\htdocs\\hongdu\\admin\\components\\news\\category.html')
with open(p, 'rb') as f:
    t = f.read().decode('utf-8')

# Find the editCategory function
idx = t.find('function editCategory')
if idx > 0:
    end = t.find('function', idx + 5)
    if end < 0:
        end = t.find('//', idx + 5)
    if end < 0:
        end = idx + 800
    print('editCategory:')
    print(t[idx:min(end, idx+800)])
    print()

# Find saveCategory function
idx = t.find('function saveCategory')
if idx > 0:
    end = t.find('function', idx + 5)
    if end < 0:
        end = idx + 1500
    print('saveCategory:')
    print(t[idx:min(end, idx+1500)])
    print()

# Check how data flows - does localStorage store strings for IDs?
ls_idx = t.find("localStorage.setItem('cms_categories'")
if ls_idx > 0:
    chunk = t[ls_idx:ls_idx+80]
    print('localStorage setItem:', chunk)

# Check how categories are loaded from API
api_idx = t.find('result.data')
if api_idx > 0:
    chunk = max(0, api_idx-100)
    print('\nAPI result processing:')
    print(t[chunk:api_idx+200])

# Check if there's string/number type mismatch on ID
id_check = t.find("c.id === id")
if id_check > 0:
    print('\nID strict comparison found at', id_check)
    print(t[max(0,id_check-10):id_check+20])

# Check renderCategories button onclick
btn_idx = t.find("editCategory(")
if btn_idx > 0:
    print('\nEdit button onclick:')
    print(t[btn_idx:btn_idx+100])
