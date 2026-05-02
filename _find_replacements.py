import os

with open('test-news-load.html', 'rb') as fp:
    raw = fp.read()
text = raw.decode('utf-8')
replacement_indices = [i for i, c in enumerate(text) if c == chr(0xFFFD)]
for idx in replacement_indices[:10]:
    start = max(0, idx-30)
    end = min(len(text), idx+30)
    context = text[start:end]
    print(f'Position {idx}: ...{repr(context)}...')
print(f'Total: {len(replacement_indices)} replacements')
print()
# Check the backup version
with open('test-news-load.html.gbk.bak', 'rb') as fp:
    raw_bak = fp.read()
# Find the same locations
for idx in replacement_indices[:5]:
    try:
        chunk = raw_bak[idx:idx+10]
        print(f'Backup bytes at {idx}: {chunk.hex()}')
    except:
        pass
print()
print('--- test-api.html ---')
with open('test-api.html', 'rb') as fp:
    raw = fp.read()
text = raw.decode('utf-8')
replacement_indices = [i for i, c in enumerate(text) if c == chr(0xFFFD)]
for idx in replacement_indices[:10]:
    start = max(0, idx-30)
    end = min(len(text), idx+30)
    context = text[start:end]
    print(f'Position {idx}: ...{repr(context)}...')
print(f'Total: {len(replacement_indices)} replacements')
