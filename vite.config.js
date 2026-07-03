import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
    ],

    // Hotspot HP: 10.47.188.216
    // Wifi Aris: 192.168.1.12
    // Wifi Kost: 192.168.1.9
    // Nobar GTA: 192.168.100.172
    // server: {
    //     host: "0.0.0.0",
    //     hmr: {
    //         host: "10.91.90.216",
    //     },
    // },
});
