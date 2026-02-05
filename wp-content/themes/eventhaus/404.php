<?php
/**
 * 404 Template
 */
get_header();
?>

<section style="padding: calc(var(--space-xl) + 80px) 0 var(--space-xl); text-align:center; min-height:60vh; display:flex; align-items:center;">
    <div class="container">
        <div style="font-family:var(--font-display); font-size:8rem; color:var(--gold-dim); line-height:1;">404</div>
        <h1 style="font-size:var(--fs-h2); margin: var(--space-sm) 0;">Page Not Found</h1>
        <p style="color:var(--text-secondary); margin-bottom:var(--space-md);">The page you're looking for doesn't exist or has been moved.</p>
        <a href="<?php echo home_url('/catalogue/'); ?>" class="btn btn--solid">
            <span>Browse Catalogue</span>
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

<?php get_footer(); ?>
