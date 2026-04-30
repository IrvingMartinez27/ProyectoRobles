<!DOCTYPE html>

<html class="light" lang="en"><head>
<meta charset="utf-8"/>
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<title>Kinetic Admin - Product Catalog</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@100;300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet"/>
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "tertiary": "#8d1c00",
                        "surface-bright": "#f9f9f9",
                        "primary-fixed-dim": "#b9c3ff",
                        "tertiary-container": "#b82800",
                        "on-secondary-fixed": "#1b1b1b",
                        "primary-fixed": "#dde1ff",
                        "on-background": "#1a1c1c",
                        "inverse-primary": "#b9c3ff",
                        "on-secondary-fixed-variant": "#474747",
                        "on-primary-fixed": "#001257",
                        "error": "#ba1a1a",
                        "surface-container-low": "#f3f3f4",
                        "tertiary-fixed-dim": "#ffb4a2",
                        "secondary": "#5e5e5e",
                        "background": "#f9f9f9",
                        "on-primary": "#ffffff",
                        "tertiary-fixed": "#ffdad2",
                        "surface-variant": "#e2e2e2",
                        "on-primary-fixed-variant": "#0033c0",
                        "on-tertiary-fixed": "#3d0700",
                        "on-surface-variant": "#434657",
                        "outline-variant": "#c4c5da",
                        "on-surface": "#1a1c1c",
                        "inverse-surface": "#2f3131",
                        "error-container": "#ffdad6",
                        "secondary-fixed-dim": "#c6c6c6",
                        "surface": "#f9f9f9",
                        "surface-container-highest": "#e2e2e2",
                        "surface-container-high": "#e8e8e8",
                        "secondary-fixed": "#e2e2e2",
                        "on-secondary": "#ffffff",
                        "on-secondary-container": "#646464",
                        "outline": "#747688",
                        "inverse-on-surface": "#f0f1f1",
                        "primary-container": "#0047ff",
                        "on-primary-container": "#d4d9ff",
                        "surface-dim": "#dadada",
                        "surface-container-lowest": "#ffffff",
                        "primary": "#0035c5",
                        "on-tertiary-fixed-variant": "#8a1c00",
                        "secondary-container": "#e2e2e2",
                        "on-tertiary": "#ffffff",
                        "surface-container": "#eeeeee",
                        "on-error": "#ffffff",
                        "surface-tint": "#0046fa",
                        "on-error-container": "#93000a",
                        "on-tertiary-container": "#ffd1c6"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "fontFamily": {
                        "headline": ["Inter"],
                        "body": ["Inter"],
                        "label": ["Inter"]
                    }
                }
            }
        }
    </script>
<style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        body { font-family: 'Inter', sans-serif; }
    </style>
<style>
    body {
      min-height: max(884px, 100dvh);
    }
  </style>
  </head>
<body class="bg-surface-container-lowest text-on-surface antialiased">
<!-- TopAppBar -->
<header class="bg-white/80 dark:bg-slate-900/80 backdrop-blur-xl docked full-width top-0 z-50 border-b border-[#c4c5da]/15 shadow-none flex items-center justify-between px-6 py-4 w-full sticky">
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-[#0035c5] dark:text-blue-500 cursor-pointer" data-icon="menu">menu</span>
<span class="font-black tracking-tighter text-xl text-[#1a1c1c] dark:text-white uppercase">KINETIC ADMIN</span>
</div>
<nav class="hidden md:flex items-center space-x-8">
<a class="text-[#1a1c1c]/50 dark:text-slate-400 font-medium hover:bg-[#f3f3f4] transition-colors px-3 py-1" href="#">Home</a>
<a class="text-[#1a1c1c]/50 dark:text-slate-400 font-medium hover:bg-[#f3f3f4] transition-colors px-3 py-1" href="#">Sales</a>
<a class="text-[#0035c5] dark:text-blue-400 font-bold px-3 py-1 border-b-2 border-[#0035c5]" href="#">Catalog</a>
<a class="text-[#1a1c1c]/50 dark:text-slate-400 font-medium hover:bg-[#f3f3f4] transition-colors px-3 py-1" href="#">Inventory</a>
<a class="text-[#1a1c1c]/50 dark:text-slate-400 font-medium hover:bg-[#f3f3f4] transition-colors px-3 py-1" href="#">Customers</a>
</nav>
<div class="flex items-center gap-4">
<span class="material-symbols-outlined text-[#1a1c1c] dark:text-slate-400 cursor-pointer" data-icon="search">search</span>
<span class="material-symbols-outlined text-[#1a1c1c] dark:text-slate-400 cursor-pointer" data-icon="account_circle">account_circle</span>
</div>
</header>
<main class="max-w-[1440px] mx-auto px-6 py-12 pb-24">
<!-- Editorial Header Section -->
<section class="mb-16 flex flex-col md:flex-row md:items-end justify-between gap-8">
<div class="max-w-2xl">
<p class="text-primary font-bold tracking-widest text-[10px] uppercase mb-4">Inventory Management</p>
<h1 class="text-5xl md:text-7xl font-bold tracking-tighter text-on-surface mb-6">Product Catalog</h1>
<p class="text-secondary text-lg leading-relaxed max-w-xl">
                    Curating the visual identity of our collection. Manage availability, metadata, and visual assets for the kinetic seasonal drop.
                </p>
</div>
<div class="flex gap-4">
<button class="bg-on-surface text-white px-8 py-4 font-bold tracking-tight hover:opacity-90 transition-all flex items-center gap-2">
<span class="material-symbols-outlined text-sm" data-icon="add">add</span>
                    New Product
                </button>
</div>
</section>
<!-- Product Filter & Grid -->
<div class="flex gap-4 mb-12 overflow-x-auto pb-2">
<button class="px-6 py-2 bg-on-surface text-white text-xs font-bold uppercase tracking-widest">All Items</button>
<button class="px-6 py-2 bg-surface-container-high text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-surface-variant transition-colors">Apparel</button>
<button class="px-6 py-2 bg-surface-container-high text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-surface-variant transition-colors">Footwear</button>
<button class="px-6 py-2 bg-surface-container-high text-on-surface text-xs font-bold uppercase tracking-widest hover:bg-surface-variant transition-colors">Accessories</button>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-y-16 gap-x-8">
<!-- Product Card 1 -->
<div class="group flex flex-col space-y-6">
<div class="aspect-[4/5] bg-surface-container-low overflow-hidden relative">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Minimalist fashion portrait of a premium black wool coat on a clean white studio background with soft architectural lighting" src="https://lh3.googleusercontent.com/aida-public/AB6AXuB4x_uPxGxt5S-qi_TO6vNnGiIkIu0cjZTCFDBijUfRP0GwzOPUcsAf4_AdfKqBPB4gYwFikpQRcn2wDd6kXhZIACGr1xLZQLkk5KNmfbgNGdkowxBrJ_0wYU-1h8n0sSag_2VxwUTosI1bSVvBPkLBl_NLpjGKorlG06LN1IGfqLpbPpNEJsZB-okY1JYjUH8N4Kmp7oTOouJ6-4CRJUtb1vXIBWSHljzKWyDzT453BHH_U-5u8ipNe9qyI7M8T1Pq100JYRACveA"/>
<div class="absolute top-4 left-4 bg-primary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">In Stock</div>
</div>
<div class="flex flex-col space-y-2">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold tracking-tight">Structured Wool Blazer</h3>
<span class="text-primary font-bold text-sm">$450.00</span>
</div>
<p class="text-secondary/60 font-mono text-[10px] tracking-widest uppercase">ID: KNT-2024-001</p>
<div class="pt-4 border-t border-outline-variant/15 mt-2">
<p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-3">Inventory Breakdown</p>
<div class="flex flex-wrap gap-2">
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">S: <span class="text-primary">12</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">M: <span class="text-primary">08</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">L: <span class="text-primary">05</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">XL: <span class="text-error">01</span></span>
</div>
</div>
</div>
</div>
<!-- Product Card 2 -->
<div class="group flex flex-col space-y-6">
<div class="aspect-[4/5] bg-surface-container-low overflow-hidden relative">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Close up shot of high-quality white organic cotton sweatshirt material showing detailed fabric texture in bright day light" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAVGgc8TxiyqGMv-SjMVW7PciNVM6cedwHIua6pBbBDAaKoXnxe4XJwjqLDKiCzsZqjQarJXzX5zQOVuWvj0B8ohBRW9fdYdHNwJzJIT-1qICJa5hPqnJfWCyWap5p1uWJsYftgZVkF-04lZoRvQrzv2RPfN3ndS-vc-lyj90olC0yfHImEwjmD6X1QesGr9hAPksLSn1G4eZZmt3qqKWaxvt0783lUKa-uA4CmIcXqhmW2-ZZcTVvfnyREUVtnAmYEIy5ldsOiQEg"/>
<div class="absolute top-4 left-4 bg-on-surface text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Low Stock</div>
</div>
<div class="flex flex-col space-y-2">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold tracking-tight">Essential Organic Crew</h3>
<span class="text-primary font-bold text-sm">$120.00</span>
</div>
<p class="text-secondary/60 font-mono text-[10px] tracking-widest uppercase">ID: KNT-2024-002</p>
<div class="pt-4 border-t border-outline-variant/15 mt-2">
<p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-3">Inventory Breakdown</p>
<div class="flex flex-wrap gap-2">
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">S: <span class="text-primary">04</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">M: <span class="text-error">02</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">L: <span class="text-primary">06</span></span>
</div>
</div>
</div>
</div>
<!-- Product Card 3 -->
<div class="group flex flex-col space-y-6">
<div class="aspect-[4/5] bg-surface-container-low overflow-hidden relative">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Professional studio photography of sleek black leather boots with polished finish on a grey reflective surface" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDkehBss3CI5YvFs4v2TI8oS1Ae3Kj75eO6HZhBe3GhwigUiR2IVKuBDe330f_huXhngE-lN24gn8xDdnfdjBtfEXe-c19IlXD4xc7hVZTDTJJ8vKGF6xFUUMhXRYWXocciUb71nYBTUE65CaN0Qgu_eGakT8xLzIh10zqwTdmXsb21KCPlaVGivmE72y_NnqpD3BZt8o0TvRE62Bye2zblkjw1WhZ06Ka5JlCUefUa1bDH-okAgel-6SL6Cj1Z8PSv1o43ICOdatA"/>
</div>
<div class="flex flex-col space-y-2">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold tracking-tight">Chrome Leather Derby</h3>
<span class="text-primary font-bold text-sm">$385.00</span>
</div>
<p class="text-secondary/60 font-mono text-[10px] tracking-widest uppercase">ID: KNT-2024-009</p>
<div class="pt-4 border-t border-outline-variant/15 mt-2">
<p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-3">Inventory Breakdown</p>
<div class="flex flex-wrap gap-2">
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">40: <span class="text-primary">15</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">42: <span class="text-primary">22</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">44: <span class="text-primary">09</span></span>
</div>
</div>
</div>
</div>
<!-- Product Card 4 -->
<div class="group flex flex-col space-y-6">
<div class="aspect-[4/5] bg-surface-container-low overflow-hidden relative">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Editorial close-up of a designer minimalist handbag in beige leather with silver hardware accents against a harsh shadow backdrop" src="https://lh3.googleusercontent.com/aida-public/AB6AXuAN7hRGBBKBrR7pyrD5260Tw2Y5oJJBhBAmV0qk5W4oAjHl77CJTVOceb2RGEdaBii8PDKhovXMgY88EcnowpiVmixIO6Zq8Qy2UQesZ7rRt6VG3RvKtWeifdenYI7W49xjM_etCqRFO1AQcB2zvIN8qXpLvll1Q67OIrxd57lJW5vyrzouMzHvcKQnNZcCEf8CSZBGu-v57b-h6R6ONFqhOy6EKpYE5pDFsxzBEdNyfx4XvTMPKxVXRjCRSDu-qe4yExm7D_ujYVM"/>
<div class="absolute top-4 left-4 bg-tertiary text-white text-[10px] font-bold px-3 py-1 uppercase tracking-widest">Out of Stock</div>
</div>
<div class="flex flex-col space-y-2">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold tracking-tight">Kinetic Tote 40</h3>
<span class="text-primary font-bold text-sm">$890.00</span>
</div>
<p class="text-secondary/60 font-mono text-[10px] tracking-widest uppercase">ID: KNT-2024-114</p>
<div class="pt-4 border-t border-outline-variant/15 mt-2">
<p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-3">Inventory Breakdown</p>
<div class="flex flex-wrap gap-2">
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">UNI: <span class="text-error">00</span></span>
</div>
</div>
</div>
</div>
<!-- Additional Row to show grid flow -->
<div class="group flex flex-col space-y-6">
<div class="aspect-[4/5] bg-surface-container-low overflow-hidden relative">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="High-end fashion photography of a crisp white cotton shirt with unique architectural collar design on a minimalist hangers" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCL3CLWQqtQf82ioF0-CIijE91jsxHOHpLOOrjRbFuKnGYvOHM6AJlpgwr4NNRTwkYUQLtUwXef8D7AAp_nWLPLnW2T7M7jtqzWGNu1dYLbZdcSUC4pyjS92B2JmAcPJDoqsRZqgyjCEJxRQ8KINUeKWnFjqEJW9APB1koA9zRfRDcw3CO0ESv-L-7VmOsbZNgkID-G4k3z9eJWNDfNd-iwWP_xwBHUUTicZC5OTw1zOWV8fDDUSE8b3TGL5REkD8ZotTLdv3ApOIQ"/>
</div>
<div class="flex flex-col space-y-2">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold tracking-tight">Articulated Oxford</h3>
<span class="text-primary font-bold text-sm">$195.00</span>
</div>
<p class="text-secondary/60 font-mono text-[10px] tracking-widest uppercase">ID: KNT-2024-042</p>
<div class="pt-4 border-t border-outline-variant/15 mt-2">
<p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-3">Inventory Breakdown</p>
<div class="flex flex-wrap gap-2">
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">M: <span class="text-primary">18</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">L: <span class="text-primary">24</span></span>
</div>
</div>
</div>
</div>
<div class="group flex flex-col space-y-6">
<div class="aspect-[4/5] bg-surface-container-low overflow-hidden relative">
<img class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105" data-alt="Flat lay photography of high-quality raw denim jeans showing selvedge detail and heavy fabric weight" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDXQvpjNZ32-VBGThfC0pdCVHTb-OFAVTezBIRY0X2gxCIKX17vymHnXa4_81FeiN8RgfFD3UjDJNGVO7laVsPcDPMtizPnnWpRSlFIqr3JAJHf5kAfb1qyBW-N0OA2ka67EzyRQMn-B1-gUK0cAUqMUacYhDon7_SFi2oTTJX5pioixdOSLySaCkm-vUuKdfHa08S6F91AAFF7vDsmax1I19hJgz-fgBkREwvRT8HsHga4P1ShsHw1x_0RM-lGg-1bQzDcZiGZdGw"/>
</div>
<div class="flex flex-col space-y-2">
<div class="flex justify-between items-start">
<h3 class="text-xl font-bold tracking-tight">14oz Raw Selvedge</h3>
<span class="text-primary font-bold text-sm">$280.00</span>
</div>
<p class="text-secondary/60 font-mono text-[10px] tracking-widest uppercase">ID: KNT-2024-056</p>
<div class="pt-4 border-t border-outline-variant/15 mt-2">
<p class="text-[10px] font-bold uppercase tracking-widest text-on-surface/40 mb-3">Inventory Breakdown</p>
<div class="flex flex-wrap gap-2">
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">30: <span class="text-primary">05</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">32: <span class="text-primary">12</span></span>
<span class="bg-surface-container text-on-surface px-3 py-1 text-[11px] font-bold">34: <span class="text-primary">08</span></span>
</div>
</div>
</div>
</div>
</div>
</main>
<!-- BottomNavBar (Mobile Only) -->
<nav class="md:hidden fixed bottom-0 w-full z-50 bg-white/80 backdrop-blur-xl border-t border-[#c4c5da]/15 shadow-[0_-4px_20px_rgba(0,0,0,0.04)] flex justify-around items-center h-16 px-4">
<div class="flex flex-col items-center justify-center text-[#1a1c1c]/50 pt-2 hover:text-[#0035c5] transition-all">
<span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest font-semibold">Home</span>
</div>
<div class="flex flex-col items-center justify-center text-[#1a1c1c]/50 pt-2 hover:text-[#0035c5] transition-all">
<span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest font-semibold">Sales</span>
</div>
<div class="flex flex-col items-center justify-center text-[#0035c5] border-t-2 border-[#0035c5] pt-2">
<span class="material-symbols-outlined" data-icon="shopping_bag">shopping_bag</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest font-semibold">Catalog</span>
</div>
<div class="flex flex-col items-center justify-center text-[#1a1c1c]/50 pt-2 hover:text-[#0035c5] transition-all">
<span class="material-symbols-outlined" data-icon="inventory_2">inventory_2</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest font-semibold">Inventory</span>
</div>
<div class="flex flex-col items-center justify-center text-[#1a1c1c]/50 pt-2 hover:text-[#0035c5] transition-all">
<span class="material-symbols-outlined" data-icon="group">group</span>
<span class="font-['Inter'] text-[10px] uppercase tracking-widest font-semibold">Customers</span>
</div>
</nav>
</body></html>