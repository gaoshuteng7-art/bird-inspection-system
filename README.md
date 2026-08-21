# 鸟情调研系统

这是一个前后端分离的机场鸟情巡视课程设计项目。

## 目录结构

- `frontend/`：uni-app/Vue 移动端前端
- `backend/`：ThinkPHP/PHP 后端及管理端源码

## 开发环境与工具

- 后端框架：BuildAdmin（基于 ThinkPHP）
- 数据库：MySQL
- 数据库管理工具：SQLyog
- 本地运行环境：phpstudy

## 核心功能

- 用户登录、验证码和 Token 鉴权
- 巡视计划新增及首页展示
- 巡视记录提交
- 巡视计划与巡视记录关联查询
- 按日期、车辆、巡视员和关键词筛选

## 运行说明

1. 后端需要配置 PHP、MySQL 和 BuildAdmin/ThinkPHP 运行环境。
2. 前端接口地址位于 `frontend/common/api.js`，启动后端后按本地环境修改。
3. 使用 SQLyog 连接 MySQL，导入 `backend/db.sql` 中的数据库结构和示例数据。
4. 依赖目录和本地环境配置未上传，请按项目环境重新安装和配置。

## 安全说明

本仓库不包含本地 `.env`、依赖目录、运行缓存和编译产物。部署前请自行配置数据库账号、缓存和运行环境。
