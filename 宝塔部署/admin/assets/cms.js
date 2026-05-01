/**
 * CMS数据集成脚本
 * 从JSON数据文件加载内容并应用到页面
 */

(function() {
    'use strict';

    // 获取当前页面ID
    function getPageId() {
        const path = window.location.pathname;
        const filename = path.split('/').pop() || 'index.html';
        return filename.replace('.html', '');
    }

    // 加载CMS数据
    async function loadCMSData() {
        const pageId = getPageId();
        
        try {
            // 优先从localStorage加载（编辑后的数据）
            const localData = localStorage.getItem(`cms_data_${pageId}`);
            if (localData) {
                const data = JSON.parse(localData);
                applyData(data);
                console.log('[CMS] 已从本地存储加载数据');
                return;
            }

            // 从JSON文件加载
            const response = await fetch(`admin/data/${pageId}.json`);
            if (response.ok) {
                const data = await response.json();
                applyData(data);
                console.log('[CMS] 已从服务器加载数据');
            }
        } catch (error) {
            console.log('[CMS] 加载数据失败:', error);
        }
    }

    // 应用数据到页面
    function applyData(data) {
        const pageId = getPageId();

        // 根据页面ID应用不同的数据映射
        const mappings = getPageMappings(pageId);
        
        mappings.forEach(mapping => {
            const element = document.querySelector(mapping.selector);
            if (element && data[mapping.field]) {
                if (mapping.type === 'html') {
                    element.innerHTML = data[mapping.field];
                } else if (mapping.type === 'attr') {
                    element.setAttribute(mapping.attr, data[mapping.field]);
                } else {
                    element.textContent = data[mapping.field];
                }
            }
        });
    }

    // 获取页面数据映射
    function getPageMappings(pageId) {
        const mappings = {
            index: [
                // Hero区域
                { selector: '.hero-title', field: 'heroTitle', type: 'text' },
                { selector: '.hero-subtitle', field: 'heroSubtitle', type: 'text' },
                { selector: '.btn-primary', field: 'heroButtonText', type: 'text' },
                { selector: '.btn-primary', field: 'heroButtonLink', type: 'attr', attr: 'href' },
                
                // 统计数据
                { selector: '.stat-card:nth-child(1) .stat-number', field: 'stat1Number', type: 'text' },
                { selector: '.stat-card:nth-child(1) .stat-label', field: 'stat1Label', type: 'text' },
                { selector: '.stat-card:nth-child(2) .stat-number', field: 'stat2Number', type: 'text' },
                { selector: '.stat-card:nth-child(2) .stat-label', field: 'stat2Label', type: 'text' },
                { selector: '.stat-card:nth-child(3) .stat-number', field: 'stat3Number', type: 'text' },
                { selector: '.stat-card:nth-child(3) .stat-label', field: 'stat3Label', type: 'text' },
                { selector: '.stat-card:nth-child(4) .stat-number', field: 'stat4Number', type: 'text' },
                { selector: '.stat-card:nth-child(4) .stat-label', field: 'stat4Label', type: 'text' },
                
                // 服务区域
                { selector: '#services .section-title', field: 'servicesTitle', type: 'text' },
                { selector: '#services .section-subtitle', field: 'servicesSubtitle', type: 'text' },
                
                // 服务卡片
                { selector: '.service-card:nth-child(1) .service-title', field: 'service1Title', type: 'text' },
                { selector: '.service-card:nth-child(1) .service-list', field: 'service1Content', type: 'html' },
                { selector: '.service-card:nth-child(2) .service-title', field: 'service2Title', type: 'text' },
                { selector: '.service-card:nth-child(2) .service-list', field: 'service2Content', type: 'html' },
                { selector: '.service-card:nth-child(3) .service-title', field: 'service3Title', type: 'text' },
                { selector: '.service-card:nth-child(3) .service-list', field: 'service3Content', type: 'html' },
                { selector: '.service-card:nth-child(4) .service-title', field: 'service4Title', type: 'text' },
                { selector: '.service-card:nth-child(4) .service-list', field: 'service4Content', type: 'html' },
                
                // 案例区域
                { selector: '#cases .section-title', field: 'casesTitle', type: 'text' },
                { selector: '#cases .section-subtitle', field: 'casesSubtitle', type: 'text' },
                
                // 优势区域
                { selector: '#advantages .advantages-title', field: 'advantagesTitle', type: 'text' },
                { selector: '#advantages .advantages-subtitle', field: 'advantagesSubtitle', type: 'text' },
                
                // FAQ区域
                { selector: '#faq .section-title', field: 'faqTitle', type: 'text' },
                { selector: '#faq .section-subtitle', field: 'faqSubtitle', type: 'text' }
            ],
            services: [
                { selector: '.page-title', field: 'pageTitle', type: 'text' },
                { selector: '.page-subtitle', field: 'pageSubtitle', type: 'text' }
            ],
            cases: [
                { selector: '.page-title', field: 'pageTitle', type: 'text' },
                { selector: '.page-subtitle', field: 'pageSubtitle', type: 'text' }
            ],
            contact: [
                { selector: '.page-title', field: 'pageTitle', type: 'text' },
                { selector: '.page-subtitle', field: 'pageSubtitle', type: 'text' }
            ]
        };

        return mappings[pageId] || [];
    }

    // 页面加载完成后执行
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', loadCMSData);
    } else {
        loadCMSData();
    }
})();
