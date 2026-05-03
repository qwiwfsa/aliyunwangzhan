<?php
$c = file_get_contents('D:\yingyong\xampp\htdocs\hongdu\news-detail.html');
echo "File size: " . strlen($c) . " bytes\n";
echo "UTF-8 BOM: " . (strpos($c, "\xEF\xBB\xBF") === 0 ? "YES" : "NO") . "\n";
echo "has loadArticleFromApi: " . (strpos($c, 'loadArticleFromApi') !== false ? "YES" : "NO") . "\n";
echo "has async function: " . (strpos($c, 'async function()') !== false ? "YES" : "NO") . "\n";
echo "old loadArticleFromStorage: " . (strpos($c, 'loadArticleFromStorage') !== false ? "STILL EXISTS" : "REMOVED") . "\n";
echo "old getAllPublishedArticles: " . (strpos($c, 'getAllPublishedArticles') !== false ? "STILL EXISTS" : "REMOVED") . "\n";

// Also check case file
$d = file_get_contents('D:\yingyong\xampp\htdocs\hongdu\case-detail.html');
echo "\n--- case-detail.html ---\n";
echo "File size: " . strlen($d) . " bytes\n";
echo "UTF-8 BOM: " . (strpos($d, "\xEF\xBB\xBF") === 0 ? "YES" : "NO") . "\n";

// Check for Chinese text readability
$sample = substr($c, 0, 500);
echo "\n--- Content sample ---\n$sample\n";
