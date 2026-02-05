<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?> x-data>

<div id="toast" class="toast"></div>

<!-- Selection Overlay -->
<div class="selection-overlay"
     :class="{ 'open': $store.quote.panelOpen }"
     @click="$store.quote.closePanel()"></div>

<!-- Selection Panel -->
<div class="selection-panel" :class="{ 'open': $store.quote.panelOpen }">
    <div class="selection-header">
        <h3 class="selection-title">Your Selection</h3>
        <button class="selection-close" @click="$store.quote.closePanel()">&times;</button>
    </div>

    <div class="selection-items">
        <template x-if="$store.quote.items.length === 0">
            <div class="selection-empty">
                <p>Your selection is empty.</p>
                <p style="margin-top: 0.5rem;">Browse the catalogue and add items to build your quote request.</p>
            </div>
        </template>

        <template x-for="item in $store.quote.items" :key="item.id">
            <div class="selection-item">
                <div class="selection-item-image">
                    <template x-if="item.image">
                        <img :src="item.image" :alt="item.name">
                    </template>
                </div>
                <div class="selection-item-info">
                    <div class="selection-item-name" x-text="item.name"></div>
                    <div class="selection-item-qty">
                        Qty: <input type="number" min="1" style="width:50px; background:var(--bg-dark); border:1px solid var(--border); color:var(--text-primary); padding:2px 4px; font-size:12px;" :value="item.qty" @change="$store.quote.updateQty(item.id, $event.target.value)">
                    </div>
                </div>
                <button class="selection-item-remove" @click="$store.quote.removeItem(item.id)">&times;</button>
            </div>
        </template>
    </div>

    <div class="selection-footer">
        <div style="display:flex; justify-content:space-between; margin-bottom:1rem; font-size:0.875rem;">
            <span style="color:var(--text-muted);">Total items</span>
            <span x-text="$store.quote.count" style="color:var(--gold); font-weight:500;"></span>
        </div>
        <a href="<?php echo home_url('/request-quote/'); ?>" class="btn btn--solid" style="width:100%; justify-content:center;" @click="$store.quote.closePanel()">
            <span>Request Quote</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</div>

<!-- Header -->
<header class="site-header">
    <div class="header-inner">
        <a href="<?php echo home_url(); ?>" class="site-logo">Event<em>Haus</em></a>

        <nav class="main-nav" id="mainNav">
            <a href="<?php echo home_url('/catalogue/'); ?>">Catalogue</a>
            <?php
            $cats = get_terms(['taxonomy' => 'rental_category', 'hide_empty' => true]);
            if ($cats && !is_wp_error($cats)) {
                foreach (array_slice($cats, 0, 4) as $cat) {
                    printf('<a href="%s">%s</a>', get_term_link($cat), esc_html($cat->name));
                }
            }
            ?>
            <a href="<?php echo home_url('/request-quote/'); ?>" class="nav-cta" @click.prevent="$store.quote.count > 0 ? (window.location='<?php echo home_url('/request-quote/'); ?>') : $store.quote.togglePanel()">
                Request Quote
                <template x-if="$store.quote.count > 0">
                    <span class="quote-count" x-text="$store.quote.count"></span>
                </template>
            </a>
        </nav>

        <button class="nav-toggle" onclick="document.getElementById('mainNav').classList.toggle('open')">
            <span></span><span></span><span></span>
        </button>
    </div>
</header>
