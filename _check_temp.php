﻿<?php
\ = new mysqli('localhost','root','','hongdu');
if(\->connect_error) die('连接失败');

\ = \->query(\"SELECT setting_key, setting_value FROM seo_settings WHERE setting_value LIKE '%Yao资金网%'\");
if(\){
    echo 'seo_settings结果数: '.\->num_rows.\"\n\";
    while(\=\->fetch_assoc()){
        echo \['setting_key'].' => '.\['setting_value'].\"\n\";
    }
}

\ = \->query(\"SELECT * FROM logo_settings WHERE logo_text LIKE '%Yao资金网%'\");
if(\){
    echo 'logo_settings结果数: '.\->num_rows.\"\n\";
    while(\=\->fetch_assoc()){
        echo json_encode(\).\"\n\";
    }
}

\ = \->query(\"SELECT * FROM footer_settings WHERE setting_value LIKE '%Yao资金网%'\");
if(\){
    echo 'footer_settings结果数: '.\->num_rows.\"\n\";
    while(\=\->fetch_assoc()){
        echo json_encode(\).\"\n\";
    }
}

// 搜索所有表中包含Yao资金网的字段
\ = \->query(\"SHOW TABLES\");
while(\ = \->fetch_row()){
    \ = \[0];
    \ = \->query(\"SHOW COLUMNS FROM \\");
    while(\ = \->fetch_assoc()){
        \ = \['Field'];
        if(strpos(\['Type'],'char') !== false || strpos(\['Type'],'text') !== false){
            \ = \"SELECT * FROM \ WHERE \\\ LIKE '%Yao资金网%' LIMIT 5\";
            \ = \->query(\);
            if(\ && \->num_rows > 0){
                echo \"\.\ 包含Yao资金网, 行数: \".\->num_rows.\"\n\";
            }
        }
    }
}

echo \"全部检查完成\n\";
\->close();
