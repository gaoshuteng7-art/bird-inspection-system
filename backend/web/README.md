### pnpm run dev或build报：
`Failed to resolve entry for package "v-code-diff". The package may have incorrect main/module/exports specified in its package.json. [plugin vite:dep-scan]`

**解决办法:**

该错误表明Vite无法通过package.json中的入口配置正确解析v-code-diff模块，通常由以下原因导致：
模块路径配置问题
手动修改node_modules/v-code-diff/package.json文件，根据Vue版本显式指定模块路径。例如对于Vue3项目，可调整exports字段为：

```
"exports": {
    ".": {
      "types": "./types/index.d.ts",
      "import": "./dist/v3/index.es.js",
      "require": "./dist/v3/index.cjs.js"
    }
},
"main": "dist/v3/index.cjs",
"module": "dist/v3/index.es.js",
```