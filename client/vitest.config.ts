import { defineConfig } from 'vitest/config'
import Vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [Vue()],
    test: {
        globals: true,
        environment: 'jsdom',
        maxThreads: 1,
        minThreads: 1,
        setupFiles: 'src/setupTests.js'
    },
})
