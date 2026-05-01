const express = require('express');
const cors = require('cors');
const mysql = require('mysql2/promise');

const app = express();
const PORT = 3000;

// MySQL数据库配置
const dbConfig = {
    host: 'localhost',
    user: 'root',
    password: '',
    database: 'hongdu_cms',
    charset: 'utf8mb4'
};

// 创建数据库连接池
const pool = mysql.createPool(dbConfig);

app.use(cors());
app.use(express.json({ limit: '50mb' }));

// 获取所有案例
app.get('/api/cases', async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM cases ORDER BY sort_order, id DESC');
        res.json({ success: true, data: rows });
    } catch (error) {
        console.error('获取案例失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 获取单个案例
app.get('/api/cases/:id', async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM cases WHERE id = ?', [req.params.id]);
        if (rows.length > 0) {
            res.json({ success: true, data: rows[0] });
        } else {
            res.status(404).json({ success: false, message: '案例不存在' });
        }
    } catch (error) {
        console.error('获取案例失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 保存案例（创建或更新）
app.post('/api/cases', async (req, res) => {
    try {
        const newCase = req.body;
        
        if (newCase.id) {
            // 更新现有案例
            await pool.execute(
                `UPDATE cases SET 
                    title = ?, company = ?, amount = ?, period = ?, 
                    category = ?, description = ?, content = ?, 
                    image = ?, status = ?, sort_order = ? 
                WHERE id = ?`,
                [
                    newCase.title, newCase.company, newCase.amount, newCase.period,
                    newCase.category, newCase.description, newCase.content,
                    newCase.image, newCase.status || 1, newCase.sort_order || 0,
                    newCase.id
                ]
            );
            res.json({ success: true, message: '更新成功', id: newCase.id });
        } else {
            // 创建新案例
            const [result] = await pool.execute(
                `INSERT INTO cases (title, company, amount, period, category, description, content, image, status, sort_order) 
                 VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)`,
                [
                    newCase.title, newCase.company, newCase.amount, newCase.period,
                    newCase.category, newCase.description, newCase.content,
                    newCase.image, newCase.status || 1, newCase.sort_order || 0
                ]
            );
            res.json({ success: true, message: '创建成功', id: result.insertId });
        }
    } catch (error) {
        console.error('保存案例失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 删除案例
app.delete('/api/cases/:id', async (req, res) => {
    try {
        await pool.execute('DELETE FROM cases WHERE id = ?', [req.params.id]);
        res.json({ success: true, message: '删除成功' });
    } catch (error) {
        console.error('删除案例失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 获取所有页面
app.get('/api/pages', async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM pages ORDER BY sort_order, id');
        res.json({ success: true, data: rows });
    } catch (error) {
        console.error('获取页面失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 获取单个页面
app.get('/api/pages/:slug', async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM pages WHERE slug = ?', [req.params.slug]);
        if (rows.length > 0) {
            res.json({ success: true, data: rows[0] });
        } else {
            res.status(404).json({ success: false, message: '页面不存在' });
        }
    } catch (error) {
        console.error('获取页面失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 保存页面
app.post('/api/pages', async (req, res) => {
    try {
        const page = req.body;
        
        if (page.id) {
            // 更新页面
            await pool.execute(
                `UPDATE pages SET title = ?, content = ?, meta_description = ?, meta_keywords = ?, status = ?, sort_order = ? WHERE id = ?`,
                [page.title, page.content, page.meta_description, page.meta_keywords, page.status || 1, page.sort_order || 0, page.id]
            );
            res.json({ success: true, message: '页面更新成功' });
        } else {
            // 创建新页面
            const [result] = await pool.execute(
                `INSERT INTO pages (slug, title, content, meta_description, meta_keywords, status, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [page.slug, page.title, page.content, page.meta_description, page.meta_keywords, page.status || 1, page.sort_order || 0]
            );
            res.json({ success: true, message: '页面创建成功', id: result.insertId });
        }
    } catch (error) {
        console.error('保存页面失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 获取所有新闻
app.get('/api/news', async (req, res) => {
    try {
        const [rows] = await pool.execute('SELECT * FROM news ORDER BY created_at DESC');
        res.json({ success: true, data: rows });
    } catch (error) {
        console.error('获取新闻失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 保存新闻
app.post('/api/news', async (req, res) => {
    try {
        const news = req.body;
        
        if (news.id) {
            await pool.execute(
                `UPDATE news SET title = ?, summary = ?, content = ?, category = ?, cover_image = ?, author = ?, status = ? WHERE id = ?`,
                [news.title, news.summary, news.content, news.category, news.cover_image, news.author, news.status || 1, news.id]
            );
            res.json({ success: true, message: '新闻更新成功' });
        } else {
            const [result] = await pool.execute(
                `INSERT INTO news (title, summary, content, category, cover_image, author, status) VALUES (?, ?, ?, ?, ?, ?, ?)`,
                [news.title, news.summary, news.content, news.category, news.cover_image, news.author, news.status || 1]
            );
            res.json({ success: true, message: '新闻创建成功', id: result.insertId });
        }
    } catch (error) {
        console.error('保存新闻失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

// 删除新闻
app.delete('/api/news/:id', async (req, res) => {
    try {
        await pool.execute('DELETE FROM news WHERE id = ?', [req.params.id]);
        res.json({ success: true, message: '删除成功' });
    } catch (error) {
        console.error('删除新闻失败:', error);
        res.status(500).json({ success: false, message: error.message });
    }
});

app.listen(PORT, () => {
    console.log(`API服务器运行在 http://localhost:${PORT}`);
    console.log('已连接到MySQL数据库: hongdu_cms');
});
